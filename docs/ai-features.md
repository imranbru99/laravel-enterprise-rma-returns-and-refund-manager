# Artificial Intelligence & Predictive Returns

## Features Implemented

### 1. Intelligent Return Reason Classification
Evaluates unstructured customer comments (e.g., *"The left earbud crackles and won't charge in the case"*) and categorizes into standard reasons (`defective`, `not_as_described`, `sizing_issue`, `customer_remorse`) with confidence score.

### 2. Predictive Fraud & Abuse Risk Scoring (0–100)
Calculates a multidimensional fraud risk score based on:
- Customer return frequency and lifetime return rate.
- Order recency and value.
- High-risk reason codes without photo evidence.
- Flags: Low Risk (0–30, auto-approval eligible), Medium Risk (31–69, manual inspection required), High Risk (70–100, manager authorization mandatory).

### 3. Customer Query Support Bot (RAG)
Grounded in tenant return policy documents (`knowledge_documents` table). Answers customer questions accurately regarding return windows, exceptions, and packaging criteria without hallucination.

### 4. Resilient Fallback Architecture
If external AI providers are offline or API keys are not supplied, the service automatically falls back to an embedded heuristic engine without crashing or rejecting requests.
