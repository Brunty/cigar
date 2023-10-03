## Building images for docker

Replace the versions as appropriate
```
docker build . -t brunty/cigar:2.0.0 -f ./docker/2.0/Dockerfile
docker push brunty/cigar --all-tags
```

