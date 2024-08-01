FROM ubuntu:latest
LABEL authors="pawel"

ENTRYPOINT ["top", "-b"]
