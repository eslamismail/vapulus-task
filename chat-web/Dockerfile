FROM node:10

# Create app directory
WORKDIR /app
ADD . /app/

# global install & update
RUN npm i

RUN npm run build

ENV HOST 0.0.0.0
EXPOSE 3000

# start command
CMD [ "npm", "start" ]