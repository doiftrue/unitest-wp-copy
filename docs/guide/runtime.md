# Runtime

"Unitest WP Copy" is not a full WordPress installation. It is a selected set of
WordPress functions and classes (symbols) that can run without a database or full
bootstrap.

## Runtime lifecycle

Runtime state is shared for the life of the PHP process:

1. **Define test constants.**
2. Call **`\Unitest_WP_Copy\WP_Runtime::boot()`** once per PHP process.
   1. Load **runtime changes for the current WordPress version**.
   2. Load **selected symbols** and runtime adapters.
   3. Start **in-memory option stores** and mockable-function support.
   4. Define **WordPress constants**.
   5. Initialize **WordPress-like globals**.
   6. Load **adapted initialization fragments**.
   7. Register the **REST OPTIONS hook**.
3. Configure required **globals, hooks, registries, and adapters**.
4. **Run the code under test.**
5. **Assert the result.**
6. Restore **shared state** in `tearDown()`.

### Optional mocks

`WP_Mock` is separate from this runtime. When a test needs its handlers, call
`\WP_Mock::bootstrap()` as well.

See [Options](/reference/options) for option configuration, lookup, mocking,
defaults, and state cleanup.

## Runtime-adapted classes

Some WordPress classes are reduced to the parts useful in unit tests:

- `\Unitest_WP_Copy\wpdb__Runtime` builds SQL but does not query a database.
- `\Unitest_WP_Copy\WP_REST_Server__Runtime` registers and dispatches routes in memory.

See [Runtime-adapted classes](/reference/runtime-classes) for extension examples,
and [`SYMBOLS-INFO.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/SYMBOLS-INFO.md) for their public methods.
