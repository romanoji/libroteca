FROM node:8.4.0

# Setup dependencies
RUN \
    APT_PACKAGES="vim curl"; \
    NPM_DEPENDENCIES="react react-dom"; \
    apt-get update \
 && apt-get install -y $APT_PACKAGES \
 && apt-get clean -y \
 && rm -rf /var/lib/apt/lists/* \
 && npm install --save $NPM_DEPENDENCIES #same as "yarn add $NPM_DEPENDENCIES"

WORKDIR /usr/src/app

ENTRYPOINT ["npm"]

CMD ["start"]
