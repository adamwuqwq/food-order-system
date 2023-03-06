# Swagger OpenAPI仕様書をHTML形式でプレビューするには
1. `npm install`
2. `npx redoc-cli build openapi.yaml -o openapi.html`

# Swagger OpenAPI仕様書をMarkdown形式でプレビューするには
1. `npm install`
2. `npx widdershins --omitHeader --code true openapi.yaml openapi.md`