FROM openjdk:8u121-jdk-alpine

ENV TZ=Asia/Tokyo \
  DB_CONNECTION=mysql \
  DB_HOST=db \
  DB_PORT=3306 \
  DB_DATABASE=laravel_local \
  DB_USERNAME=phper \
  DB_PASSWORD=secret \
  LOG_CHANNEL=stder \
  DRIVER_URL=http://search.maven.org/remotecontent?filepath=mysql/mysql-connector-java/8.0.16/mysql-connector-java-8.0.16.jar \
  APP_URL=https://github.com/schemaspy/schemaspy/releases/download/v6.0.0-rc2/schemaspy-6.0.0-rc2.jar

WORKDIR /app

RUN apk --update add graphviz ttf-dejavu && \
    apk --update add --virtual .builddep tzdata wget libressl && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    wget -O mysql-connector-java.jar ${DRIVER_URL} && \
    wget -O schemaspy.jar ${APP_URL} && \
    apk del .builddep && \
    rm -rf /var/cache/apk/*
