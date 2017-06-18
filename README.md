# Cigar

[![Build Status](https://travis-ci.org/Brunty/cigar.svg?branch=master)](https://travis-ci.org/Brunty/cigar)

A smoke testing tool inspired by [symm/vape](https://github.com/symm/vape)

This is not ready for real use yet, I'm just playing around...

The code is awful.

## Installation

Install via composer:

`composer require brunty/cigar --dev`

## To use

Create a `.cigar` file that contains a newline separated list with the URLs you want to check, followed by a space, followed by the expected status code and optionally, a quoted string of content you want to see within the response body.

```
http://httpbin.org/status/418 418 "teapot"
http://httpbin.org/status/200 200
http://httpbin.org/status/304 304
http://httpbin.org/status/500 500
```

Then run `bin/cigar` to have it check each of the URLs return the status code expected.

```
> bin/cigar                                           
✓ http://httpbin.org/status/418 [418:418] 
✓ http://httpbin.org/status/200 [200:200] 
✓ http://httpbin.org/status/304 [304:304] 
✓ http://httpbin.org/status/500 [500:500] 
```

The format of the lines in the output is:

```
pass/fail url [expected_code:actual_code]
```

If all tests pass, the return code `$?` will be `0` - if any of them don't return the expected status code, the return code will be `1`

If you wish to suppress the output of the test run, pass the `--quiet` option to the command: `bin/cigar --quiet`

## Contributing

This started as a small personal project.

Although this project is small, openness and inclusivity are taken seriously. To that end a code of conduct (listed in the contributing guide) has been adopted.

[Contributor Guide](CONTRIBUTING.md)
