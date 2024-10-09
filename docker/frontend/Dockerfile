# Stage 1: Build
FROM node:18-alpine as build-stage

# Set working directory
WORKDIR /frontend

# Copy package.json and package-lock.json
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy all files to the container
COPY . .

# Build the Nuxt 3 application
RUN npm run build

# Stage 2: Production
FROM node:18-alpine as production-stage

# Set working directory
WORKDIR /frontend

# Copy only necessary files from build stage
COPY --from=build-stage /app/.output /app/.output
COPY --from=build-stage /app/node_modules /app/node_modules
COPY --from=build-stage /app/package.json /app/package.json

# Install PM2 to manage the process
RUN npm install pm2 -g

# Expose the application port
EXPOSE 3000

# Start the application
CMD ["pm2-runtime", ".output/server/index.mjs"]