#!/bin/bash

# Vercel build script for Laravel with Vite
echo "Building Laravel project with Vite..."

# Install dependencies
npm install

# Build Vite assets
npm run build

echo "Build completed successfully!"
