<template x-if="block.block_type === 'hero'">
    @include('admin.content.partials.blocks.hero')
</template>
<template x-if="block.block_type === 'text'">
    @include('admin.content.partials.blocks.text')
</template>
<template x-if="block.block_type === 'stats'">
    @include('admin.content.partials.blocks.stats')
</template>
<template x-if="block.block_type === 'faq'">
    @include('admin.content.partials.blocks.faq')
</template>
<template x-if="block.block_type === 'cta'">
    @include('admin.content.partials.blocks.cta')
</template>
<template x-if="block.block_type === 'process_steps'">
    @include('admin.content.partials.blocks.process-steps')
</template>
<template x-if="block.block_type === 'text_with_stats'">
    @include('admin.content.partials.blocks.text-with-stats')
</template>
<template x-if="block.block_type === 'articles'">
    @include('admin.content.partials.blocks.articles')
</template>
<template x-if="block.block_type === 'testimonials'">
    @include('admin.content.partials.blocks.testimonials')
</template>
<template x-if="block.block_type === 'origins'">
    @include('admin.content.partials.blocks.origins')
</template>
<template x-if="block.block_type === 'export_map'">
    @include('admin.content.partials.blocks.export-map')
</template>
<template x-if="block.block_type === 'contact'">
    @include('admin.content.partials.blocks.contact')
</template>
