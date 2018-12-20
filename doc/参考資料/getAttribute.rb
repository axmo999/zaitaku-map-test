# encoding: utf-8
require 'csv'
require 'net/http'
require 'openssl'
require 'uri'
require 'json'
require 'logger'
require 'nkf'
require 'date'
require 'mysql2'
require 'dotenv'

Dotenv.load "../../.env"
$hostname = ENV["DB_HOST"]
$username = ENV["DB_USERNAME"]
$password = ENV["DB_PASSWORD"]
$database = ENV["DB_DATABASE"]
$socket = ENV["DB_SOCKET"]

$client = Mysql2::Client.new(:socket => $socket, :username => $username, :password => $password, :database => $database)
# 文字コードをUTF8に設定
$client.query("set character set utf8")

class Entity
    attr_accessor :facility_id, :question_cd, :answer_cd, :answer_conent
end

def getFacilityId(facilityName)
    if facilityName.nil?
        return nil
    end

    # DBに問い合わせ
    statement = $client.prepare("SELECT id FROM facilities where facility_name = ?")
    result = statement.execute(facilityName).first

    if result.nil?
        p facilityName
    end
    return result["id"]
end

def getQuestionCd(questionName)
    if questionName.nil?
        return nil
    end

    # DBに問い合わせ
    statement = $client.prepare("SELECT question_cd, answer_group_cd FROM m_question_cds where question_content = ?")
    result = statement.execute(questionName).first

    return result["question_cd"].to_s, result["answer_group_cd"].to_s
end

def getAttribute(contents, questionName, facility_id)
    if contents.nil?
        return nil
    end
    row = []
    questionCd, answer_group_cd = getQuestionCd(questionName)

    contents_split = contents.split("|")
    contents_split.each do |content|
        statement = $client.prepare("SELECT answer_cd FROM m_answer_cds where answer_content = ? and answer_group_cd = ?")
        result = statement.execute(content, answer_group_cd).first
        row.push([facility_id, questionCd, result["answer_cd"]])
    end

    return row
end

def getBool(contents, questionName, facility_id)
    if contents.nil?
        contents = 0
    end
    row = []
    questionCd, answer_group_cd = getQuestionCd(questionName)

    contents_split = contents.split("|")
    contents_split.each do |content|
        statement = $client.prepare("SELECT answer_cd FROM m_answer_cds where answer_content = ? and answer_group_cd = ?")
        result = statement.execute(content, answer_group_cd).first
        row.push([facility_id, questionCd, result["answer_cd"]])
    end

    return row
end

CSV.open("attribute.csv", "w", :force_quotes => true) do |csv|

    header = [
                    "facility_id",
                    "question_cd",
                    "answer_cd",
                    "answer_conent",
                    "created_at",
                    "updated_at"
                ]
    csv << header

    CSV.foreach('WordPress.csv', headers: true) do |data|
        facility_id = getFacilityId(data["Title"])
        if !data["受け入れ可"].nil?
            rows = []
            rows = getAttribute(data["受け入れ可"], "受け入れ可", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["受け入れ可（その他）"].nil?
            rows = []
            rows = getAttribute("TEXT", "受け入れ可（その他）", facility_id)
            rows.each do |row|
                row.push(data["受け入れ可（その他）"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["訪問、対応地域"].nil?
            rows = []
            rows = getAttribute(data["訪問、対応地域"], "訪問、対応地域", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["訪問、対応地域（その他）"].nil?
            rows = []
            rows = getAttribute("TEXT", "訪問、対応地域（その他）", facility_id)
            rows.each do |row|
                row.push(data["訪問、対応地域（その他）"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["在宅医療の取り組み"].nil?
            rows = []
            rows = getAttribute(data["在宅医療の取り組み"], "在宅医療の取り組み", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["在宅管理可能な医療行為"].nil?
            rows = []
            rows = getAttribute(data["在宅管理可能な医療行為"], "在宅管理可能な医療行為", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["医師への面談方法"].nil?
            rows = []
            rows = getAttribute("TEXT", "医師への面談方法", facility_id)
            rows.each do |row|
                row.push(data["医師への面談方法"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["主治医意見書等の連絡先"].nil?
            rows = []
            rows = getAttribute("TEXT", "主治医意見書等の連絡先", facility_id)
            rows.each do |row|
                row.push(data["主治医意見書等の連絡先"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["担当者会議へ医師の参加（医療機関で開催の場合）"].nil?
            rows = []
            rows = getAttribute(data["担当者会議へ医師の参加（医療機関で開催の場合）"], "担当者会議へ医師の参加（医療機関で開催の場合）", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["担当者会議へ医師の参加（往診時に開催の場合）"].nil?
            rows = []
            rows = getAttribute(data["担当者会議へ医師の参加（往診時に開催の場合）"], "担当者会議へ医師の参加（往診時に開催の場合）", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["他職種の方々への要望"].nil?
            rows = []
            rows = getAttribute("TEXT", "他職種の方々への要望", facility_id)
            rows.each do |row|
                row.push(data["他職種の方々への要望"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["認知症サポート医"].nil?
            rows = []
            rows = getBool(data["認知症サポート医"], "認知症サポート医", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["特定事業所加算の有無"].nil?
            rows = []
            rows = getBool(data["特定事業所加算の有無"], "特定事業所加算の有無", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["男性スタッフの有無"].nil?
            rows = []
            rows = getBool(data["男性スタッフの有無"], "男性スタッフの有無（対応の有無）", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["診療科目（その他）"].nil?
            rows = []
            rows = getAttribute("TEXT", "診療科目（その他）", facility_id)
            rows.each do |row|
                row.push(data["診療科目（その他）"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["在宅療養支援歯科診療所の届け出"].nil?
            rows = []
            rows = getBool(data["在宅療養支援歯科診療所の届け出"], "在宅療養支援歯科診療所の届け出", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["虫歯・歯周病治療"].nil?
            rows = []
            rows = getAttribute(data["虫歯・歯周病治療"], "虫歯・歯周病治療", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["入れ歯の修理・作製"].nil?
            rows = []
            rows = getAttribute(data["入れ歯の修理・作製"], "入れ歯の修理・作製", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["摂食嚥下・リハビリテーション"].nil?
            rows = []
            rows = getAttribute(data["摂食嚥下・リハビリテーション"], "摂食嚥下・リハビリテーション", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["口腔ケア"].nil?
            rows = []
            rows = getAttribute(data["口腔ケア"], "口腔ケア", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["その他治療"].nil?
            rows = []
            rows = getAttribute("TEXT", "その他治療", facility_id)
            rows.each do |row|
                row.push(data["その他治療"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["男性スタッフの有無（対応の有無）"].nil?
            rows = []
            rows = getBool(data["男性スタッフの有無（対応の有無）"], "男性スタッフの有無（対応の有無）", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["土日、祝日の対応"].nil?
            rows = []
            rows = getBool(data["土日、祝日の対応"], "土日、祝日の対応", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["夜間対応"].nil?
            rows = []
            rows = getBool(data["夜間対応"], "夜間対応", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["小児"].nil?
            rows = []
            rows = getBool(data["小児"], "小児", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["成人"].nil?
            rows = []
            rows = getBool(data["成人"], "成人", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["精神"].nil?
            rows = []
            rows = getBool(data["精神"], "精神", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["理学療法士"].nil?
            rows = []
            rows = getBool(data["理学療法士"], "理学療法士", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["作業療法士"].nil?
            rows = []
            rows = getBool(data["作業療法士"], "作業療法士", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["言語聴覚士"].nil?
            rows = []
            rows = getBool(data["言語聴覚士"], "言語聴覚士", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["認定特定行為業務従事者認定"].nil?
            rows = []
            rows = getBool(data["認定特定行為業務従事者認定"], "認定特定行為業務従事者認定", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["通院等乗降介助"].nil?
            rows = []
            rows = getBool(data["通院等乗降介助"], "通院等乗降介助", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["利用可能"].nil?
            rows = []
            rows = getAttribute(data["利用可能"], "利用可能", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["薬の一包化"].nil?
            rows = []
            rows = getBool(data["薬の一包化"], "薬の一包化", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["袋に月日"].nil?
            rows = []
            rows = getBool(data["袋に月日"], "袋に月日", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["朝昼夕の記載"].nil?
            rows = []
            rows = getBool(data["朝昼夕の記載"], "朝昼夕の記載", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["錠剤の粉砕"].nil?
            rows = []
            rows = getBool(data["錠剤の粉砕"], "錠剤の粉砕", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["担当者会議への参加"].nil?
            rows = []
            rows = getBool(data["担当者会議への参加"], "担当者会議への参加", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["入所施設"].nil?
            rows = []
            rows = getAttribute(data["入所施設"], "入所施設", facility_id)
            rows.each do |row|
                row.push(data["入所施設"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["入所条件"].nil?
            rows = []
            rows = getAttribute(data["入所条件"], "入所条件", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["定員"].nil?
            rows = []
            rows = getAttribute("TEXT", "定員", facility_id)
            rows.each do |row|
                row.push(data["定員"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["部屋（個室）"].nil?
            rows = []
            rows = getAttribute("TEXT", "部屋（個室）", facility_id)
            rows.each do |row|
                row.push(data["部屋（個室）"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["部屋（２人部屋）"].nil?
            rows = []
            rows = getAttribute("TEXT", "部屋（２人部屋）", facility_id)
            rows.each do |row|
                row.push(data["部屋（２人部屋）"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["部屋（その他）"].nil?
            rows = []
            rows = getAttribute("TEXT", "部屋（その他）", facility_id)
            rows.each do |row|
                row.push(data["部屋（その他）"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["月額利用料"].nil?
            rows = []
            rows = getAttribute("TEXT", "月額利用料", facility_id)
            rows.each do |row|
                row.push(data["月額利用料"], Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["看護職配置"].nil?
            rows = []
            rows = getAttribute(data["看護職配置"], "看護職配置", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["夜間勤務"].nil?
            rows = []
            rows = getAttribute(data["夜間勤務"], "夜間勤務", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["２４時間緊急連絡体制"].nil?
            rows = []
            rows = getBool(data["２４時間緊急連絡体制"], "２４時間緊急連絡体制", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

        if !data["栄養士の有無"].nil?
            rows = []
            rows = getBool(data["栄養士の有無"], "栄養士の有無", facility_id)
            rows.each do |row|
                row.push(nil, Date.parse(data["Date"].to_s).to_s, Date.parse(data["Post Modified Date"].to_s).to_s)
                csv << row
            end
        end

    end

end

$client.close


