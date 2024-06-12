FROM node:alpine3.18

RUN apk update --no-cache
RUN apk upgrade
RUN apk add nano bash --no-cache
RUN npm install -g pm2@latest
RUN npm fund

RUN mkdir -p /node
WORKDIR /node
RUN npm init --yes
RUN npm install express

COPY . .

EXPOSE 8080
CMD ["pm2-runtime","app.js", "--name", "sys-ops.id"]
