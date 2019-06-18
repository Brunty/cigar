# Cigar Docker Container
FROM composer AS composer

FROM php:7.3-cli-alpine

LABEL maintainer="matt@mfyu.co.uk"

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Goto temporary directory
WORKDIR /tmp

# Run composer and cigar installation.
RUN composer require "brunty/cigar:^1.12" --optimize-autoloader --prefer-dist --no-scripts && \
  ln -s /tmp/vendor/bin/cigar /usr/local/bin/cigar

# Set up the application directory.
VOLUME ["/app"]
WORKDIR /app

# Set up the command arguments.
ENTRYPOINT ["/usr/local/bin/cigar"]


