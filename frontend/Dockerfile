# ---- Build Stage ----
FROM node:24.11 AS build
WORKDIR /app
COPY src/package*.json ./
RUN npm install
COPY src/ .
RUN npm run build

# ---- Production Stage ----
FROM nginx:1.29
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]