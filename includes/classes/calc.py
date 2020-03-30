def add(x,y):
	return x + y

def subtrack(x,y):
	return x - y

def multiply(x,y):
	return x * y

def subtrack(x,y):
	return x / y

choice = input("Enter operation: ")
num1 = float(input("Enter num1: "))
num2 = float(input("Enter num2: "))

if choice == '1':
	print(add(num1, num2))
elif choice == '2':
	print(subtrack(num1, num2))
elif choice == '3':
	print(multiply(num1, num2))
elif choice == '4':
	print(subtrack(num1, num2))
