FROM node:20-alpine

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build
RUN chmod +x docker/start.sh

EXPOSE 8001
CMD ["sh", "docker/start.sh"]
