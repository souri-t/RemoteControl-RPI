FROM alpine:latest
MAINTAINER souri-t <souri-t@github.com>

# Install Python
RUN apk --update --no-cache add python3 python3-dev musl-dev py3-pip
RUN pip3 install --upgrade pip
RUN pip3 install watchdog

# Install pigpio
RUN apk --update --no-cache add git make gcc
RUN git clone https://github.com/joan2937/pigpio.git && \ 
    cd ./pigpio && \
    make && \
    make install DESTDIR=/ install

EXPOSE 8888

CMD ["/usr/bin/svscan", "/etc/svscan/"]
