# Cigar

[![Build Status](https://travis-ci.org/Brunty/cigar.svg?branch=master)](https://travis-ci.org/Brunty/cigar)

A smoke testing tool inspired by [symm/vape](https://github.com/symm/vape)

This is not ready for real use yet, I'm just playing around...

The code is awful.

## To use

Create a `.cigar` file that contains a newline separated list with the URLs you want to check, followed by a space, followed by the expected status code:

```
http://httpbin.org/status/418 418
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

If all tests pass, the return code `$?` will be `0` - if any of them don't return the expected status code, the return code will be `1`

If you wish to suppress the output of the test run, pass the `--quiet` option to the command: `bin/cigar --quiet`
