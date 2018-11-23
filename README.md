# Cigar

[![Build Status](https://travis-ci.org/Brunty/cigar.svg?branch=master)](https://travis-ci.org/Brunty/cigar) [![Coverage Status](https://coveralls.io/repos/github/Brunty/cigar/badge.svg?branch=master)](https://coveralls.io/github/Brunty/cigar?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/d89a0b55-8ce6-4f85-a09c-7852d986225f/mini.png)](https://insight.sensiolabs.com/projects/d89a0b55-8ce6-4f85-a09c-7852d986225f)

A smoke testing tool inspired by [symm/vape](https://github.com/symm/vape)

Similar tools include:

* [Blackfire Player](https://github.com/blackfireio/player)

## Installation

Install via composer:

`composer require brunty/cigar --dev`

## To use

Create a `.cigar.json` file that contains an array of json objects specifying the `url`, `status`, (optional) `content`, and  (optional) `content-type` to check.

```
[
  {
    "url": "http://httpbin.org/status/418",
    "status": 418,
    "content": "teapot"
  },
  {
    "url": "http://httpbin.org/status/200",
    "status": 200,
    "content-type": "text/html"
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
✓ http://httpbin.org/status/200 [200:200] [text/html:text/html] 
✓ http://httpbin.org/status/304 [304:304] 
✓ http://httpbin.org/status/500 [500:500] 
```

The format of the lines in the output is:

```
pass/fail url [expected_code:actual_code] [optional_expected_content-type:optional_actual_content-type] optional_text
```

If all tests pass, the return code `$?` will be `0` - if any of them don't return the expected status code, the return code will be `1`

### Quiet test mode

If you wish to suppress the output of the test run, pass the `--quiet` option to the command: `vendor/bin/cigar --quiet`

### Alternative configuration files

If you wish to use an alternative configuration file, use the `vendor/bin/cigar -c file.json` or `vendor/bin/cigar --config=file.json` options when running the command.

### Passing a base URL to check against

If you wish to check a file of URLs relative to the root of a site against a base URL, you can do so with by using 
`vendor/bin/cigar -u http://httpbin.org` or `vendor/bin/cigar --url=http://httpbin.org`

Your configuration file can then contain URLs including:

* Full absolute URLs as before (cigar won't use the base URL when checking an absolute URL)

```
[
  {
    "url": "http://httpbin.org/status/418",
    "status": 418,
    "content": "teapot"
  }
]
```

* URLs relative to the base url that you've specified, either with or without a leading slash.

 ```
[
  {
    "url": "/status/418",
    "status": 418,
    "content": "teapot"
  },
  
  {
    "url": "status/418",
    "status": 418,
    "content": "teapot"
  }
]
```

### Disabling SSL cert verification

If you wish to run the tool without checking SSL certs, use the `-i` or `--insecure` option to the command: 
`vendor/bin/cigar -i` or `vendor/bin/cigar --insecure`

**Only use this if absolutely necessary.**

### Passing Authorization header

If you wish to add the Authorization header, use the `-a` or `--auth` option to the command: 
`vendor/bin/cigar -a "Basic dXNyOnBzd2Q="` or `vendor/bin/cigar --auth="Basic dXNyOnBzd2Q="`

### Passing custom header

If you wish to add any custom header(s), use the `-h` or `--header` option to the command:
`vendor/bin/cigar -h "Cache-control: no-cache"` or `vendor/bin/cigar --header="Cache-control: no-cache"`

### Command help & command version

If you want to see all the available options for Cigar, use the command: `vendor/bin/cigar --help`

If you wish to see what version of Cigar you're using, use the command: `vendor/bin/cigar --version`

## Contributing

This started as a small personal project.

Although this project is small, openness and inclusivity are taken seriously. To that end a code of conduct (listed in the contributing guide) has been adopted.

[Contributor Guide](CONTRIBUTING.md)
