# Cigar Docker Container
FROM composer AS composer

FROM php:8.2-cli-alpine

LABEL maintainer="matt@brunty.me"

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Goto temporary directory
WORKDIR /tmp

# Run composer and cigar installation.
RUN composer require "brunty/cigar:^2.0" --optimize-autoloader --prefer-dist --no-scripts && \
  ln -s /tmp/vendor/bin/cigar /usr/local/bin/cigar

# Set up the application directory.
VOLUME ["/app"]
WORKDIR /app

# Set up the command arguments.
ENTRYPOINT ["/usr/local/bin/cigar"]


