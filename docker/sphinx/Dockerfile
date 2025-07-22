FROM alpine:3.18

RUN apk add --no-cache sphinx

RUN mkdir -p /var/run/sphinxsearch

COPY sphinx.conf /etc/sphinx/sphinx.conf
