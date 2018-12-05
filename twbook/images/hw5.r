tab = read.table("./datahwk1.txt")
mat = as.matrix(tab)
mat = matrix(mat, ncol=ncol(mat), dimnames=NULL)
x = mat[1,]
M = 2
n = length(x)
cor=matrix()
for (i in seq(from=1, to=M+1)) {
	# cat("&&&: ", x[n:i], "\n")
	# cat("***: ", x[(n-i+1):1], "\n\n")
	v0 = t(matrix(x[n:i]))
	v1 = matrix(x[(n-i+1):1])
	cor[i] = (v0 %*% v1) / length(v0)
}
R=toeplitz(cor[-length(cor)])
r=matrix(cor[-1])
w=solve(R, r)
a=matrix(c(1, -w))
sigma2 = cor %*% a
cat("auto-correlation vector `r' is:", cor, "\n")
cat("AR coefficients vector `a' is:", a, "\n")
cat("innovation variance `sigma^2' is:", sigma2, "\n")
# R_inverse = solve(R)
# w0 = R_inverse %*% r 
# cat("Wiener filter is:", w0, "\n")
cat("Wiener filter is:", w, "\n")
Jmin = cor[1] - t(r) %*% w
cat("Estimated J_min is:", Jmin, "\n")

sum=0
x_bar = matrix()
for (i in seq(from=2, to=n-1)) {
	v = t(matrix(c(x[i], x[i-1])))
	sum = sum + (v %*% w - x[i+1])^2
	x_bar[i-1] = v %*% w
}
Jmin_p = sum / (n-1)
cat("experimental J_min is:", Jmin_p, "\n")

plot(x[-1:-2], type='l', col='red')
par(new=TRUE)
plot(x_bar, type='l', col='green')
