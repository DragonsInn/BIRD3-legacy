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
#EXPOSE 4878
#EXPOSE 80
#EXPOSE 443
#EXPOSE 22
#EXPOSE 143
#EXPOSE 993
#EXPOSE 25
#EXPOSE 53

# Run!
CMD [ "npm", "start" ]
