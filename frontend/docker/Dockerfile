FROM node:8.4.0

# Setup dependencies
RUN \
    APT_PACKAGES="vim"; \
    apt-get update \
 && apt-get install -y $APT_PACKAGES \
 && apt-get clean -y \
 && rm -rf /var/lib/apt/lists/* \

WORKDIR /usr/src/app

# Setup npm dependencies
RUN yarn

ENTRYPOINT ["npm"]

CMD ["start"]
