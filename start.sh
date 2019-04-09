init() {
	echo "todo"
}
up() {
	docker-compose up -d
}
build() {
	docker run -it -v $PWD/backend:/srv/backend -v $PWD/frontend:/srv/frontend builder:latest bash
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
