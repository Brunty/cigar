## Building images for docker

Replace the versions as appropriate
```
docker build . -t brunty/cigar:1.12.4 -f ./docker/1.12/Dockerfile
docker push brunty/cigar --all-tags
```

