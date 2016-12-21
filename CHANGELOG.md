# Change Log

### 1.1.0

- Allow 'context' replacements in log messages to conform either to Symfony Translation component conventions (e.g. `"%key%", ['%key%' => 'value']`) or to Symfony ConsoleLogger conventions (e.g. `"{key}", ['key' => 'value']`). Simple keys that begin and end with alphanumeric characters are still wrapped in `{}`, whereas other keys are used as-is.

### 1.0.3

- README improvements
- Run code sniffer prior to tests

### 1.0.2

- Remove unused components from composer.json

### 1.0.1

- Fix branch alias
