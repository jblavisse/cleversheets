# Étape 1: Construction de l'application
FROM node:lts as build-stage

WORKDIR /app

COPY frontend/package*.json ./

RUN npm install

COPY frontend/. .

RUN npm run build && npm run generate

# Étape 2: Création de l'image de production
FROM nginx:alpine as production-stage

WORKDIR /app

RUN rm -rf /usr/share/nginx/html/*

COPY ./docker/frontend/nginx/default.conf /etc/nginx/conf.d

COPY --from=build-stage /app/.output/public /usr/share/nginx/html

RUN chmod -R 755 /usr/share/nginx/html

EXPOSE 3000

CMD ["nginx", "-g", "daemon off;"]