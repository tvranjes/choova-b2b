# Magento 2 Development Guidelines

You are assisting with Magento 2 development. This project uses Magento Bricklayer
for AI-assisted development tooling.

## MCP Server: magento-bricklayer

You have access to an MCP server with 84 tools for Magento development.
Use `search-tools` to discover relevant tools and `code-runner` for multi-step operations.
Always prefer using these tools over assumptions about the codebase.

### Before Modifying Magento Code

Magento resolves DI, plugins, preferences, and events at runtime across many modules.
Reading source files alone misses overrides from other modules. **Always check runtime
state before writing code that touches existing classes.**

| Task | Check runtime state first | Then load guidelines |
|------|---------------------------|----------------------|
| Writing or modifying a plugin | `check-class className=Target\Class` | `development-context category=plugin` |
| Overriding or extending a class | `check-class className=Target\Class` | (based on what you find) |
| Injecting or changing DI config | `di-configuration className=Target\Class` | (based on what you find) |
| Working with product data | `eav-attributes entityType=catalog_product` | `development-context category=eav` |
| Working with customer data | `eav-attributes entityType=customer` | `development-context category=eav` |
| Creating/modifying a DB table | `database-schema table=table_name` | `development-context category=model` |
| Subscribing to an event | `event-list eventName=event_name` | `development-context category=observer` |
| Adding a REST API endpoint | `api-endpoints` | `development-context category=rest-api` |
| Writing a GraphQL resolver | `graphql-inspect target=types` | `development-context category=graphql` |
| Customizing a layout / moving a block | `layout-inspect handle=<handle>` | `development-context category=frontend` |
| Modifying an admin grid or form | `ui-component-inspect name=<name>` | `development-context category=ui-component` |
| Adding or debugging a queue consumer | `message-queue-inspect` | `development-context category=message-queue` |
| Creating a cron job | `system-status check=cron` | `development-context category=cron` |
| Debugging an error | `diagnose-error` | (based on diagnosis) |
| Investigating performance | `diagnose-performance` | `development-context category=performance` |
| Writing **any** PHP file | — | `development-context category=coding-standards` (always) |

### Development Context Tool

Before writing or generating code, call the `development-context` tool with the relevant
task category to load coding guidelines and development patterns.

| Category | Description |
|----------|-------------|
| **Hyvä Theme** | |
| `hyva-theme` | Hyvä theme setup and Alpine.js CSP components |
| `hyva-theme-advanced` | Hyvä ViewModels, module compatibility, and customization |
| `hyva-ui-component` | Hyvä UI component CSS and design system |
| `hyva-ui-component-js` | Hyvä UI component Alpine.js and interactivity |
| `hyva-checkout` | Hyvä Checkout architecture and integration development |
| `hyva-checkout-config` | Hyvä Checkout XML configuration and layout |
| `hyva-checkout-api` | Hyvä Checkout evaluation, form, and frontend APIs |
| `magewire` | Magewire V1 reactive component development |
| `magewire-three` | Magewire 3 component development and V1 migration |
| **Module Development** | |
| `module` | Module scaffolding and structure |
| `model` | Model, repository, and data layer development |
| `plugin` | Plugin (interceptor) development |
| `observer` | Event observer development |
| `preference` | Class preference (rewrite) development |
| `eav` | EAV attribute and entity development |
| `data-patch` | Data and schema patch development |
| **API & Integration** | |
| `rest-api` | REST API endpoint development |
| `graphql` | GraphQL schema and resolver development |
| `payment` | Payment method module setup and configuration |
| `payment-gateway` | Payment gateway components (builders, handlers, validators) |
| `payment-checkout` | Payment checkout integration and frontend |
| `shipping` | Shipping carrier integration |
| `message-queue` | Message queue and async processing |
| `import` | Custom import entity development |
| `export` | Custom export entity development |
| **Frontend & Admin** | |
| `frontend` | Frontend development (layout, templates, JS) |
| `theme` | Theme structure, layout XML, and templates |
| `theme-styling` | Theme LESS/CSS styling and JavaScript |
| `checkout` | Checkout custom steps and layout processors |
| `checkout-advanced` | Checkout config providers, mixins, and validation |
| `adminhtml` | Admin panel development |
| `ui-component` | Admin UI component grids |
| `ui-component-form` | Admin UI component forms |
| **System & Quality** | |
| `cron` | Cron job development |
| `indexer` | Custom indexer development |
| `testing` | Unit, integration, and API testing |
| `coding-standards` | PHP coding standards, syntax, formatting, and quality rules |
| `security` | Security best practices and guidelines |
| `performance` | Performance optimization guidelines |
| `list` | See all categories with skill/guideline counts |

### Reminder

Always call `development-context category=coding-standards` before writing any PHP file.
See the "Before Modifying Magento Code" table above for task-specific guidelines.

### Token Efficiency

Follow these patterns to minimize context usage when working with Bricklayer tools:

**Prefer `code-runner` for multi-step operations:**
Instead of chaining multiple individual tool calls, write PHP code in `code-runner` to execute
them in a single call. Example: to get data for 5 products, use one `code-runner` call with a
`foreach` loop over `repo()`, not 5 separate `product-get` calls. Call `code-runner-help` for
available helpers and example patterns.

**Discover tools before using them:**
Call `search-tools` with a keyword to find relevant tools. Use `detail=names` first for a
lightweight overview, then `detail=full` only for the tools you need.

**Minimize list tool payloads:**
- `count_only=true` — check result size before fetching full data
- `fields=sku,name,price` — request only the columns you need
- `verbosity=minimal` — get just identifiers from `module-list` and `eav-attributes`

**Use `search-docs` before `development-context`:**
`search-docs` returns a lightweight pointer to the right category. Only call
`development-context` once you know which category you need.

**Use `batch-execute` for repetitive operations:**
When performing the same tool call with different parameters (e.g., updating stock for
10 products), use `batch-execute` with a JSON array instead of 10 separate calls.

**Truncate log output:**
Use `max_entry_length` on `log` (action=read or action=search) to limit long entries.
Start with `max_entry_length=500` and increase only if you need full stack traces.

## Shell Commands

This project runs in a **DDEV** environment. When running Magento CLI commands:

```bash
ddev exec bin/magento <command>
```

Common commands after code changes:
- `ddev exec bin/magento setup:upgrade` - Run database migrations after adding/updating modules
- `ddev exec bin/magento setup:di:compile` - Compile dependency injection (required after DI changes)
- `ddev exec bin/magento cache:clean` - Clear cache
- `ddev exec bin/magento cache:flush` - Flush cache storage
- `ddev exec bin/magento indexer:reindex` - Rebuild indexes
- `ddev exec composer install` - Install dependencies
- `ddev exec composer require <package>` - Add new dependencies

---

*Generated by magento-bricklayer*
*Timestamp: 2026-09-11 10:26:51 CEST*
*Agent: claude-code*