init() {
	echo "aaaaaaaaaaa"
}
up() {
	echo "bbbbbbbbbbb"
}
build() {
	echo "cccccccccccccccc"
}

case "$1" in
	init)
		init
		;;
	up)
		up
		;;
	build)
		build
		;;
esac
