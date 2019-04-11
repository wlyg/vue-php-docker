init() {
	docker pull ubuntu:latest
	docker pull mongo:latest
	docker pull php:7.2-fpm
	docker pull nginx:latest
	sudo echo "127.0.0.1 api.test.com" >> /etc/hosts
	sudo echo "127.0.0.1 frontend.test.com" >> /etc/hosts
}
up() {
	cd $PWD/docker
	docker-compose up -d
}
stop() {
	cd $PWD/docker
	docker-compose stop
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
	stop)
		stop
		;;
	build)
		build
		;;
esac
