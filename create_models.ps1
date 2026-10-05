$models = @(
    "BusinessSetting",
    "SocialAccount",
    "FacebookPage",
    "InstagramAccount",
    "WebhookEvent",
    "Conversation",
    "ConversationMessage",
    "Comment",
    "AutomationRule",
    "AutoReplyRule",
    "AutoCommentRule",
    "BusinessKnowledge",
    "Service",
    "Product",
    "Faq",
    "AiConversation",
    "AiMessage",
    "Lead",
    "LeadActivity",
    "LeadNote",
    "ContentPost",
    "ScheduledPost",
    "Media",
    "ActivityLog",
    "SystemSetting"
)

foreach ($model in $models) {
    php artisan make:model $model -m
}
