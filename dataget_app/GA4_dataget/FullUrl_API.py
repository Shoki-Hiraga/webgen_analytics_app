import sys
import os
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '..')))

from setting_file.header import *
from setting_file.setFunc import get_db_config
from setting_file.GA4_Set.GA4_dateSet import generate_monthly_date_ranges
from setting_file.GA4_Set.GA4_date_Duplicate_FullUrl import record_exists
# from setting_file.GA4_Set.GA4_QshURL_Maker import URLS


SESSION_MEDIUM_FILTER = "organic"

# 認証設定
SERVICE_ACCOUNT_FILE = api_json.qsha_oh_ga4
PROPERTY_ID = '307515371'
credentials = service_account.Credentials.from_service_account_file(SERVICE_ACCOUNT_FILE)
client = BetaAnalyticsDataClient(credentials=credentials)

# .env 読み込み
load_dotenv(dotenv_path=os.path.join(os.path.dirname(__file__), '..', 'config', '.env'))

# DB接続関数
def get_db_connection():
    try:
        config = get_db_config()
        conn = mysql.connector.connect(**config)
        print("[DEBUG] DB接続成功")
        return conn
    except Exception as e:
        print(f"[CRITICAL DB ERROR] DB接続に失敗しました: {e}")
        traceback.print_exc()
        raise  # エラーを再スローしてメイン処理も止める
# 
def get_landing_urls_from_db():
    try:
        conn = get_db_connection()
        cursor = conn.cursor()

        query = "SELECT url FROM ga4_fullurl_listurls WHERE is_active = TRUE"
        cursor.execute(query)

        urls = [row[0] for row in cursor.fetchall()]
        print(f"[DEBUG] {len(urls)} 件のURLを取得")

        cursor.close()
        conn.close()
        return urls

    except Exception as e:
        print(f"[DB ERROR] URL取得中にエラー: {e}")
        traceback.print_exc()
        return []

# 全セッション数を取得
def get_total_sessions_from_landing(landing_url):
    dimension_filter = FilterExpression(
        and_group=FilterExpressionList(
            expressions=[
                FilterExpression(filter=Filter(
                    field_name="landingPagePlusQueryString",
                    string_filter={"match_type": "CONTAINS", "value": landing_url}
                )),
                FilterExpression(filter=Filter(
                    field_name="sessionMedium",
                    string_filter={"match_type": "EXACT", "value": SESSION_MEDIUM_FILTER}
                ))
            ]
        )
    )

    request = RunReportRequest(
        property=f"properties/{PROPERTY_ID}",
        dimensions=[Dimension(name="landingPagePlusQueryString")],
        metrics=[Metric(name="sessions")],
        date_ranges=[DateRange(start_date=set_start_date, end_date=set_end_date)],
        dimension_filter=dimension_filter
    )

    try:
        response = client.run_report(request)
        return sum(int(row.metric_values[0].value or 0) for row in response.rows)
    except Exception as e:
        print(f"[ERROR] Total sessions error for {landing_url}: {e}")
        return 0

# CV数を取得
def get_cv_sessions_from_landing(landing_url):
    dimension_filter = FilterExpression(
        and_group=FilterExpressionList(
            expressions=[
                FilterExpression(filter=Filter(
                    field_name="landingPagePlusQueryString",
                    string_filter={"match_type": "CONTAINS", "value": landing_url}
                )),
                FilterExpression(filter=Filter(
                    field_name="pagePath",
                    string_filter={"match_type": "EXACT", "value": "/thanks/"}
                )),
                FilterExpression(filter=Filter(
                    field_name="isKeyEvent",
                    string_filter={"match_type": "EXACT", "value": "true"}
                )),
                FilterExpression(filter=Filter(
                    field_name="eventName",
                    string_filter={"match_type": "EXACT", "value": "査定依頼完了"}
                )),
                FilterExpression(filter=Filter(
                    field_name="sessionMedium",
                    string_filter={"match_type": "EXACT", "value": SESSION_MEDIUM_FILTER}
                ))
            ]
        )
    )

    request = RunReportRequest(
        property=f"properties/{PROPERTY_ID}",
        dimensions=[Dimension(name="landingPagePlusQueryString")],
        metrics=[Metric(name="keyEvents")],
        date_ranges=[DateRange(start_date=set_start_date, end_date=set_end_date)],
        dimension_filter=dimension_filter
    )

    try:
        response = client.run_report(request)
        return sum(int(row.metric_values[0].value or 0) for row in response.rows)
    except Exception as e:
        print(f"[ERROR] CV count error for {landing_url}: {e}")
        return 0

# DBに保存
def insert_into_db(landing_url, session_medium, total_sessions, cv_count, cvr, start_date, end_date):
    try:
        print(f"[DEBUG] Insert: URL={landing_url}, Medium={session_medium}, Sessions={total_sessions}, "
              f"CVs={cv_count}, CVR={cvr}, Start={start_date}, End={end_date}")

        conn = get_db_connection()
        cursor = conn.cursor()

        insert_query = """
            INSERT INTO ga4_fullurl 
            (landing_url, session_medium, total_sessions, cv_count, cvr, start_date, end_date, created_at, updated_at)
            VALUES (%s, %s, %s, %s, %s, %s, %s, NOW(), NOW())
        """

        values = (
            landing_url,
            session_medium,
            int(total_sessions or 0),
            int(cv_count or 0),
            float(cvr or 0.0),
            start_date,
            end_date
        )

        cursor.execute(insert_query, values)
        print("[DEBUG] SQL実行成功")

        conn.commit()
        print("[DEBUG] コミット成功")

        cursor.close()
        conn.close()
        print("[DEBUG] DB接続クローズ完了")

    except Exception as e:
        print(f"[DB ERROR] DB挿入中にエラーが発生しました: {e}")
        traceback.print_exc()

# メイン処理
date_ranges = generate_monthly_date_ranges()

try:
    URLS = get_landing_urls_from_db()
    print(f"[INFO] 開始 URLS = {URLS}")
    for start_date, end_date in date_ranges:
        set_start_date = start_date.strftime("%Y-%m-%d")
        set_end_date = end_date.strftime("%Y-%m-%d")
        db_start_date = start_date
        db_end_date = end_date

        print(f"[INFO] 処理期間: {set_start_date} ～ {set_end_date}")

        for landing_url in URLS:
            # 重複チェックを実行
            if record_exists(landing_url, SESSION_MEDIUM_FILTER, db_start_date, db_end_date):
                print(f"[SKIP] 既に登録済み: {landing_url}, {db_start_date} - {db_end_date}")
                continue  # スキップして次へ

            print(f"[INFO] 処理中: {landing_url}")
            total_sessions = get_total_sessions_from_landing(landing_url)
            cv_count = get_cv_sessions_from_landing(landing_url)

            cvr = round((cv_count / total_sessions) * 100, 2) if total_sessions > 0 else 0.0

            print(f'[INFO] ランディングページ: {landing_url}, セッション: {total_sessions}, CV: {cv_count}, CVR: {cvr:.2f}%')

            insert_into_db(
                landing_url=landing_url,
                session_medium=SESSION_MEDIUM_FILTER,
                total_sessions=total_sessions,
                cv_count=cv_count,
                cvr=cvr,
                start_date=db_start_date,
                end_date=db_end_date
            )

            delay = random.uniform(3.0, 4.5)
            print(f'[DEBUG] 遅延 {delay:.2f} 秒\n')
            time.sleep(delay)

except Exception as err:
    print(f'\n[CRITICAL ERROR] エラーが発生しました: {err}')
