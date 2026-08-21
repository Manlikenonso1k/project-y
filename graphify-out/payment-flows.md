# Current Payment and Notification Flows

```mermaid
flowchart TD
    Checkout[CheckoutController::process] --> Order[Order]
    Checkout --> Success[Order success page]
    Success --> BTC[Pay with Bitcoin]
    Success --> GC[Pay with Gift Card]

    BTC --> PaymentController[PaymentController::showOrderPayment]
    PaymentController --> Address[Blockonomics new address]
    Address --> Callback[GET /api/blockonomics/callback]
    Callback --> CallbackLog[blockonomics_callbacks]
    Callback -->|status >= 2| Paid[Order payment_status = paid]
    Paid --> BTCNotify[Telegram confirmed-payment message]

    GC --> GiftCardPage[gift-card-payment.blade.php]
    GiftCardPage --> Submit[GiftCardPaymentController::submit]
    Submit --> Submission[gift_card_submissions]
    Submit --> PrivateFiles[storage/app/private/gift-card-submissions/{order}]
    Submission --> GiftNotify[Telegram review message]
    PrivateFiles --> GiftPhotos[Telegram sendPhoto for each image]
    GiftNotify --> Review[Manual review]
    GiftPhotos --> Review
    Review -->|approved by staff| Paid
```

## Important constraints

- Blockonomics only changes an order to paid after callback status `>= 2`.
- Gift-card submissions always begin as `pending_review`; no automatic approval is implemented.
- Gift-card images are private server files and are sent directly to the configured Telegram group.
- Required deployment configuration: `BLOCKONOMICS_API_KEY`, `BLOCKONOMICS_CALLBACK_SECRET`, `TELEGRAM_BOT_TOKEN`, and `TELEGRAM_CHAT_ID`.
