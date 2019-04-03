init() {
	echo "aaaaaaaaaaa"
}
up() {
	docker-compose up -d
}
build() {
	docker run -it php:latest bash
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
