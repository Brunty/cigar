# Cigar Docker Container
FROM composer/composer
LABEL maintainer="matt@mfyu.co.uk"

# Goto temporary directory
WORKDIR /tmp

# Run composer and cigar installation.
RUN composer selfupdate && \
  composer require "brunty/cigar:~1.12.1" --optimize-autoloader --prefer-dist --no-scripts && \
  ln -s /tmp/vendor/bin/cigar /usr/local/bin/cigar

# Set up the application directory.
VOLUME ["/app"]
WORKDIR /app

# Set up the command arguments.
ENTRYPOINT ["/usr/local/bin/cigar"]
