#!/bin/bash

set -e

REGISTRY="${REGISTRY:-superti4r}"
IMAGE_NAME="${IMAGE_NAME:-esaturasi}"
BUILD_TAG="${BUILD_TAG:-latest}"
PLATFORMS="${PLATFORMS:-linux/amd64,linux/arm64}"

echo "Building with Docker Buildx v0.34.0+"
echo "Platforms: $PLATFORMS"
echo "Registry: $REGISTRY"
echo "Image: $REGISTRY/$IMAGE_NAME:$BUILD_TAG"

docker buildx build \
  --platform $PLATFORMS \
  --tag $REGISTRY/$IMAGE_NAME:$BUILD_TAG \
  --tag $REGISTRY/$IMAGE_NAME:$BUILD_TAG-$(date +%s) \
  --cache-from type=gha \
  --cache-to type=gha,mode=max \
  --push \
  -f docker/Dockerfile \
  .

echo "Build completed successfully"
echo "Image: $REGISTRY/$IMAGE_NAME:$BUILD_TAG"
