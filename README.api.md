# API Map (Web)

Este documento mapeia as chamadas de API do cliente web, seus contratos de request/response e onde cada chamada é consumida.

## 1) Contrato padrão de resposta HTTP

As rotas HTTP internas do web seguem o contrato:

```json
{
  "status": true,
  "message": "texto",
  "data": {},
  "http": 200
}
```

Referência de validação do contrato no cliente:
- `ensureApiResponseContract` em `src/lib/api-contract.ts`.

## 2) Headers obrigatórios de contexto do dispositivo

Headers enviados pelo cliente quando disponíveis:
- `x-device-id`
- `x-partner-id` (quando há parceiro selecionado)

Origem/append dos headers:
- `appendClientDeviceHeaders` em `src/modules/system/device-validation/request-headers.ts`.

Validação server-side de UUID/contexto:
- `validateDeviceId` e `validateDeviceRequestContext` em `src/lib/device-request.ts`.

---

## 3) Endpoints HTTP internos (Next.js)

### 3.1 GET `/api/client/home`
### 3.2 GET `/api/client/debitos`
### 3.3 GET `/api/client/pagamento`
### 3.4 GET `/api/client/comprovante`

**Implementação**
- Rotas delegam para `buildClientPageResponse`.

**Request**
- Query params livres (`Record<string, string>`)
- Header esperado: `x-device-id` válido (UUID)

**Response (sucesso)**
```json
{
  "status": true,
  "message": "Requisição validada com sucesso.",
  "data": {
    "page": "home|debitos|pagamento|comprovante",
    "timestamp": "ISO",
    "query": {},
    "deviceId": "uuid"
  },
  "http": 200
}
```

**Response (erro de device)**
```json
{
  "status": false,
  "message": "UUID do dispositivo ...",
  "data": {
    "page": "...",
    "timestamp": "ISO"
  },
  "http": 401
}
```

**Consumidor no cliente**
- `fetchProjectPagePayload` em `src/modules/project/services/page-api.ts`.

---

### 3.5 GET `/api/system/request-config`

Endpoint de diagnóstico do ambiente de validação de requests.

**Response (sucesso)**
```json
{
  "status": true,
  "message": "Configuração de ambiente carregada.",
  "data": {
    "isEnvValid": true,
    "envValidation": {
      "endpoint": { "value": "...", "valid": true },
      "httpStatusValidationRules": {
        "value": "4xx,500,401",
        "valid": true,
        "invalidTokens": [],
        "normalizedMatchers": ["4xx", "500", "401"]
      }
    },
    "examples": {}
  },
  "http": 200
}
```

---

## 4) Chamadas HTTP para backend externo

### 4.1 POST `${NEXT_PUBLIC_API_BASE_URL}${NEXT_PUBLIC_DEVICES_API_PATH}` (default: `/devices`)

**Objetivo**
- Validar dispositivo no carregamento do app (`DeviceValidationGuard` -> `validateDevice`).

**Request**
```json
{
  "deviceId": "uuid",
  "environment": "development|homologation|staging|production"
}
```

**Observações de parsing**
- A resposta de parceiro/ativo é normalizada por `resolveEnabled` e `parsePartners` em `validation-api.ts`.
- O request passa por `executeManagedRequest`, com validação de códigos HTTP configurada por `NEXT_PUBLIC_HTTP_STATUS_VALIDATION_RULES`.

---

## 5) Server Actions (não são rotas HTTP REST públicas)

### 5.1 `handleProcessarPagamento(dadosPagamento, deviceContext)`
- Arquivo: `src/app/actions/pagamento.ts`
- Exige `deviceContext` válido (`deviceId + partnerId`) via `validateDeviceRequestContext`.

**Retorno**
- Sucesso aprovado:
```ts
{ status: 'aprovado', idTransacao: string, message: string }
```
- Sucesso recusado:
```ts
{ status: 'recusado', idTransacao: string, message: string }
```
- Erro:
```ts
{ status: 'error', message: string }
```

### 5.2 `getLogTransacao(transactionId, cpf, deviceContext)`
- Arquivo: `src/app/actions/pagamento.ts`
- Exige `deviceContext` válido.

**Retorno**
- Sucesso:
```ts
{ status: 'success', data: LogTransacao }
```
- Erro:
```ts
{ status: 'error', message: string }
```

---

## 6) Arquivos únicos de acesso API por módulo (estado atual)

- Núcleo HTTP genérico: `src/lib/api.ts`
- Request gerenciado + regras HTTP: `src/lib/request-manager.ts`
- Módulo de páginas do projeto: `src/modules/project/services/page-api.ts`
- Módulo de validação de dispositivo: `src/modules/system/device-validation/validation-api.ts`

Esses arquivos concentram request/response para seus respectivos domínios no web.

---

## 7) Contrato de rotas do backend (`app_api`)

Prefixos oficiais:
- Cliente: `/api/client/v1`
- Admin: `/api/admin/v1`

Organização dos arquivos de rota:
- `routes/api.php` concentra os prefixos de versão e domínio (`client` e `admin`)
- `routes/api/client/v1.php` contém somente as rotas internas do client (sem repetir prefixo global)
- `routes/api/admin/v1.php` contém somente as rotas internas do admin (sem repetir prefixo global)

Esse é o contrato para o frontend: qualquer mudança nesses prefixos impacta consumo de API.

## 8) Regra de ativação de mock

Variáveis de ambiente utilizadas:
- `ENV_TYPE`
- `SERVER_TYPE`
- `MOCK_ENABLED`

Regra:
- Mock só pode ser ativado quando `MOCK_ENABLED=true` **e** `ENV_TYPE` **e** `SERVER_TYPE` estiverem em `development` ou `homologation`.
- Em qualquer outro cenário (ex.: `staging`, `production`), o mock deve permanecer desativado.

Exemplo válido para mock:
```env
ENV_TYPE=development
SERVER_TYPE=homologation
MOCK_ENABLED=true
```

Exemplo inválido para mock:
```env
ENV_TYPE=production
SERVER_TYPE=production
MOCK_ENABLED=true
```

---

## 9) Arquitetura HTTP x Módulos (`app_api`)

Regra obrigatória:
- Controllers que recebem `Request` HTTP devem ficar em `app/Http/Controllers`.
- Módulos (`app/Modules/...`) devem concentrar regra de negócio, serviços e exceções.

Estrutura atual:
- Client HTTP: `app/Http/Controllers/Client/V1`
- Admin HTTP: `app/Http/Controllers/Admin/V1`
- Regra de negócio isolada: `app/Modules/{Client,Admin}/Services` e `app/Modules/{Client,Admin}/Exceptions`

Observação:
- Rotas (`routes/api/client/v1.php`, `routes/api/client/devices.php`, `routes/api/admin/v1.php`) devem importar apenas controllers em `App\Http\Controllers\...`.

## 10) Módulo compartilhado de Entity

`Entity` foi modularizado para uso compartilhado por `client` e `admin`.

Estrutura:
- `app/Modules/Entity/Contracts`
- `app/Modules/Entity/Exceptions`
- `app/Modules/Entity/Services`
- `app/Modules/Entity/Models`

Implementação atual:
- Contrato: `App\Modules\Entity\Contracts\EntityFinder`
- Serviço: `App\Modules\Entity\Services\EntityFinderService`
- Model: `App\Modules\Entity\Models\Entity`

Os controllers HTTP de admin/client consomem o contrato via injeção de dependência.

## 11) Módulo compartilhado de Device

`Device` foi modularizado para uso compartilhado por `client` e `admin`.

Estrutura:
- `app/Modules/Device/Contracts`
- `app/Modules/Device/Exceptions`
- `app/Modules/Device/Services`
- `app/Modules/Device/Models`

Implementação atual:
- Contrato: `App\Modules\Device\Contracts\DeviceManager`
- Serviço: `App\Modules\Device\Services\DeviceManagerService`
- Model: `App\Modules\Device\Models\Device`

O controller HTTP de client consome o contrato via injeção de dependência.

## 12) Módulo compartilhado de Partner

`Partner` foi modularizado para uso compartilhado por `client` e `admin`.

Estrutura:
- `app/Modules/Partner/Contracts`
- `app/Modules/Partner/Exceptions`
- `app/Modules/Partner/Services`
- `app/Modules/Partner/Models`

Implementação atual:
- Contrato: `App\Modules\Partner\Contracts\PartnerManager`
- Serviço: `App\Modules\Partner\Services\PartnerManagerService`
- Model: `App\Modules\Partner\Models\Partner`

O controller HTTP de admin consome o contrato via injeção de dependência.
