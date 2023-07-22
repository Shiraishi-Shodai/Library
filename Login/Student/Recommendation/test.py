import sys
import json
import matplotlib.pyplot as plt

# 標準入力を取得
line = sys.stdin.read()
# jsonから辞書型に変換
line = json.loads(line)
print(line["b"])
print(line["c"])

a = [1, 2, 3, 4, 5, 6,]
b = [10, 20, 30, 40, 10, 60,]

plt.plot(a, b, c="tomato")
plt.title("test")
print(plt.show())


