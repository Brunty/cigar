# Cigar

[![Build Status](https://travis-ci.org/Brunty/cigar.svg?branch=master)](https://travis-ci.org/Brunty/cigar) [![Coverage Status](https://coveralls.io/repos/github/Brunty/cigar/badge.svg?branch=master)](https://coveralls.io/github/Brunty/cigar?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/d89a0b55-8ce6-4f85-a09c-7852d986225f/mini.png)](https://insight.sensiolabs.com/projects/d89a0b55-8ce6-4f85-a09c-7852d986225f)

A smoke testing tool inspired by [symm/vape](https://github.com/symm/vape)

## Installation

Install via composer:

`composer require brunty/cigar --dev`

## To use

Create a `.cigar.json` file that contains an array of json objects specifying the `url`, `status` and (optional) `content` to check.

```
[
  {
    "url": "http://httpbin.org/status/418",
    "status": 418,
    "content": "teapot"
  },
  {
    "url": "http://httpbin.org/status/200",
    "status": 200
  },
  {
    "url": "http://httpbin.org/status/304",
    "status": 304
  },
  {
    "url": "http://httpbin.org/status/500",
    "status": 500
  }
]
```

Then run `vendor/bin/cigar` to have it check each of the URLs return the status code expected.

```
> vendor/bin/cigar                                           
✓ http://httpbin.org/status/418 [418:418] teapot
✓ http://httpbin.org/status/200 [200:200] 
✓ http://httpbin.org/status/304 [304:304] 
✓ http://httpbin.org/status/500 [500:500] 
```

The format of the lines in the output is:

```
pass/fail url [expected_code:actual_code] optional_text
```

If all tests pass, the return code `$?` will be `0` - if any of them don't return the expected status code, the return code will be `1`

### Quiet test mode

If you wish to suppress the output of the test run, pass the `--quiet` option to the command: `vendor/bin/cigar --quiet`

### Alternative configuration files

If you wish to use an alternative configuration file, use the `vendor/bin/cigar -c file.json` or `vendor/bin/cigar --config=file.json` options when running the command.

### Command help & command version

If you want to see all the available options for Cigar, use the command: `vendor/bin/cigar --help`

If you wish to see what version of Cigar you're using, use the command: `vendor/bin/cigar --version`

## Contributing

This started as a small personal project.

Although this project is small, openness and inclusivity are taken seriously. To that end a code of conduct (listed in the contributing guide) has been adopted.

[Contributor Guide](CONTRIBUTING.md)
