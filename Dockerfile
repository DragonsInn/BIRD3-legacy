FROM node:11

# Install global tools first...
#RUN npm i -g yarn

# Deps:
RUN npm install -g "@babel/node" "@babel/core@^7.1"

# Volumes
VOLUME /srv/bird3

# Change to the bird3 folder...
WORKDIR /srv/bird3


# Expose all the ports...there's a lot of them.
# RPC: Use only on localhost, or export for slaves.
#EXPOSE 4878

# Web, http/s: Public
##     HTTP HTTPS
EXPOSE 80   443

# SSH: Public
##     SSH
EXPOSE 22

# mailin.io: Public
##     IMAP IMAP Sec
EXPOSE 143  993
##     SMTP
EXPOSE 25

# Custom DNS: Public
##     DNS
EXPOSE 53

# LDAP: Private
##     LDAP LDAPS
EXPOSE 389  636

# Run!
ENTRYPOINT [ "babel-node", "src/Entrypoints/NodeJS/index.js" ]
