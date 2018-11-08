FROM node:11

# Install global tools first...
RUN npm i -g yarn

# Create folders
RUN mkdir /srv/bird3
RUN mkdir /srv/bird3/src
RUN mkdir /srv/bird3/config
RUN mkdir /srv/bird3/data

# Change to the bird3 folder...
WORKDIR /srv/bird3

# Install deps - use yarn, it's faster.
COPY package*.json .
COPY yarn.lock .
RUN yarn install

# Copy source...
COPY src /srv/bird3/src

# Expose all the ports...there's a lot of them.
# RPC: Use only on localhost, or export for slaves.
#EXPOSE 4878

# Web, http/s: Public
#EXPOSE 80
#EXPOSE 443

# SSH: Public
#EXPOSE 22

# mailin.io: Public
## IMAP
#EXPOSE 143
## IMAP Secure
#EXPOSE 993
## SMTP
#EXPOSE 25

# Custom DNS: Public
#EXPOSE 53

# LDAP: Private
## Plain
#EXPOSE 389
## Secure
#EXPOSE 636


# Run!
CMD [ "node", "src/Entrypoints/NodeJS/index.js" ]
