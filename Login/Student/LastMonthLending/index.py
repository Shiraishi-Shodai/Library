import pandas as pd
import matplotlib.pyplot as plt
import numpy as np
import sys
import json
import japanize_matplotlib
import datetime
from datetime import timedelta
import calendar
from sklearn.linear_model import LinearRegression


# 月初を求める関数(Y-m-d)
def get_first_date(d):
    return d.replace(day=1)


# 月末を求める関数(Y-m-d)
def get_last_date(d):
    return d.replace(day=calendar.monthrange(d.year, d.month)[1])


# PHPから渡された変数をひとつの文字列として取得
# line = sys.stdin.read()
# # jsonからオブジェクト型に変換(ここでは辞書型に変換)
# data = json.loads(line)


# dir = {
#     "lent_time": data["lent_time"],
#     "count": np.array(data["count"]).astype(int)
# }

# テスト用データフレーム
dir2 = {
    "lent_time": ["2023-07-11", "2023-07-12", "2023-07-13", "2023-07-21"],
    "count": np.array(["20", "12", "2", "3"]).astype(int)
}

df = pd.DataFrame(dir2)

today = datetime.date.today()
first = get_first_date(today)
last = get_last_date(today)


lent_time = []
count = []
totalBooks = []

# 月初から月末までループ
for i in range((last - first).days + 1):

    # 月初に一日ずつ足していく（timedeletaは時間の差を表す)
    date = first + timedelta(i)
    # %Y-%m-%d形式でdateを文字列化
    str_date = int(date.strftime("%d"))
    test_date = date.strftime("%Y-%m-%d")

    # 日付の追加
    lent_time.append(str_date)
    # 0で初期化
    count.append(0)

    # iが0でないとき一個後ろの要素を取得
    if i != 0:
        totalBooks.append(totalBooks[i - 1])
    else:
        totalBooks.append(0)

    # df行数分ループ
    for j in df.index:

        # 貸出があった日かどうかの判定
        if df.loc[j, "lent_time"] == test_date:
            # その日、貸出された回数を代入
            count[i] = df.loc[j, "count"]
            # 貸出総数を増加
            totalBooks[i] = totalBooks[i] + df.loc[j, "count"]
            break

# lent_time,countリストをnumpy化
nLent_time = np.array(lent_time)
nCount = np.array(count)

# subplotを設定
ax = plt.subplot(111)

# グリッドを設定
ax.grid()
# X軸の範囲を月初から月末に設定
ax.set_xlim(nLent_time[0], nLent_time[-1])

# x軸y軸のラベルを設定
ax.set_xlabel("貸出日")
ax.set_ylabel("貸出回数")

# 散布図を描画
ax.scatter(nLent_time, nCount, label="記録した貸出回数")

# 単回帰モデルを準備
model = LinearRegression()
# 月初から現在までのデータを用意
# reshape(1, -1)は行数を2行に設定して列数は自動計算という意味。Tで転置
X = nLent_time[0:today.day].reshape(1, -1).T
y = nCount[0:today.day]

# 現在までの貸出回数と日付を学習させる
model.fit(X, y)
# 傾き
coef = model.coef_[0]
# 切片
intercept = model.intercept_
# 月末(%d)
lastDay = nLent_time[-1]
# 予測
predict = model.predict([[lastDay]])[0]


# 1~100までを100分割したnumpyを用意
X_line = np.linspace(1, lastDay, 100)

#  総冊数を描画

# 回帰直線を求める
y_line = coef * X_line + intercept
# 回帰直線を引く
ax.plot(X_line, y_line, c='b', label="回帰直線")


titleText = f'今月の貸出状況(今月は{predict:.0f}冊程度貸出があると予測されます)'

# Axesのタイトルを設定
ax.set_title(titleText, c='r', fontsize=18)
plt.legend(fontsize=10)
plt.show()
