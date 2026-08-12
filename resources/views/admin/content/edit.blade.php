@extends('admin.layouts.app')
@section('title', 'Edit ' . $page->title)
@section('page_title', 'Edit Page: ' . $page->title)

@section('content')
<form action="{{ route('admin.content.update', $page) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Editor -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Page Info Card -->
            <div class="card-modern">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-900">Page Information</h3>
                    <a href="{{ route('admin.content.index') }}" class="text-xs text-slate-500 hover:text-indigo-600 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Pages
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required class="mt-1">
                    </div>
                    <div>
                        <label for="slug">URL Slug</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-mono text-slate-400">/</span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}" required class="pl-7">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Blocks Editor Card -->
            <div class="card-modern" x-data="blockEditor({{ json_encode($page->blocks->toArray()) }})">
                <!-- Top Language Navigation Tabs Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-100 p-2 rounded-lg border border-slate-200 mb-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider px-2">Language Editor:</span>
                        <button type="button"
                                @click="activeTab = 'en'"
                                :class="activeTab === 'en' ? 'bg-white text-indigo-600 shadow-2xs font-bold border border-slate-200' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                class="px-4 py-2 text-xs rounded-lg transition-all flex items-center gap-2">
                            <span>🇬🇧</span>
                            <span>English Content (EN)</span>
                        </button>
                        <button type="button"
                                @click="activeTab = 'id'"
                                :class="activeTab === 'id' ? 'bg-emerald-600 text-white shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                class="px-4 py-2 text-xs rounded-lg transition-all flex items-center gap-2">
                            <span>🇮🇩</span>
                            <span>Konten Bahasa Indonesia (ID)</span>
                        </button>
                    </div>
                    <div class="text-xs text-slate-600 font-medium px-2">
                        Active View: <span class="font-bold" :class="activeTab === 'en' ? 'text-indigo-600' : 'text-emerald-700'" x-text="activeTab === 'en' ? '🇬🇧 English (EN)' : '🇮🇩 Bahasa Indonesia (ID)'"></span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Content Section Blocks</h3>
                        <p class="text-xs text-slate-500">Drag to reorder page sections or add new block components.</p>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="addBlock('hero')" class="btn btn-secondary py-1 px-2.5 text-xs">+ Hero</button>
                        <button type="button" @click="addBlock('text')" class="btn btn-secondary py-1 px-2.5 text-xs">+ Text</button>
                        <button type="button" @click="addBlock('stats')" class="btn btn-secondary py-1 px-2.5 text-xs">+ Stats</button>
                        <button type="button" @click="addBlock('faq')" class="btn btn-secondary py-1 px-2.5 text-xs">+ FAQ</button>
                        <button type="button" @click="addBlock('cta')" class="btn btn-secondary py-1 px-2.5 text-xs">+ CTA</button>
                        <button type="button" @click="addBlock('process_steps')" class="btn btn-secondary py-1 px-2.5 text-xs">+ Process Steps</button>
                        <button type="button" @click="addBlock('text_with_stats')" class="btn btn-secondary py-1 px-2.5 text-xs">+ Text+Stats</button>
                        <button type="button" @click="addBlock('articles')" class="btn btn-secondary py-1 px-2.5 text-xs">+ Articles</button>
                        <button type="button" @click="addBlock('testimonials')" class="btn btn-secondary py-1 px-2.5 text-xs">+ Testimonials</button>
                    </div>
                </div>

                <div class="space-y-4" x-ref="blockList">
                    <template x-for="(block, index) in blocks" :key="block.id">
                        <div class="rounded-lg border border-slate-200 bg-slate-50/50 p-4 transition-all hover:border-slate-300"
                             x-data="{ collapsed: false }"
                             :class="{'ring-2 ring-indigo-300': collapsed === false}">
                            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-200/60 cursor-move">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400 cursor-grab" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                    </svg>
                                    <span class="font-bold text-xs uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100" x-text="label(block)"></span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded border"
                                          :class="activeTab === 'en' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
                                          x-text="activeTab === 'en' ? '🇬🇧 EN' : '🇮🇩 ID'"></span>
                                    <span class="text-[10px] text-slate-400 ml-1" x-text="summary(block)"></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="collapsed = !collapsed" class="text-xs text-slate-500 hover:text-slate-700 px-2 py-1 hover:bg-slate-100 rounded transition-colors" :title="collapsed ? 'Expand' : 'Collapse'">
                                        <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <button type="button" @click="duplicateBlock(index)" class="text-xs text-slate-500 hover:text-indigo-600 px-2 py-1 hover:bg-indigo-50 rounded transition-colors" title="Duplicate Block">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                    <button type="button" @click="removeBlock(index)" class="text-xs text-rose-600 hover:text-rose-700 font-medium px-2 py-1 hover:bg-rose-50 rounded transition-colors">Remove</button>
                                </div>
                            </div>

                            <div x-show="!collapsed">
                                <input type="hidden" :name="'blocks['+index+'][id]'" :value="block.id">
                                <input type="hidden" :name="'blocks['+index+'][block_type]'" :value="block.block_type">
                                <input type="hidden" :name="'blocks['+index+'][order]'" :value="index">
                                <input type="hidden" :name="'blocks['+index+'][is_visible]'" value="1">
                                {{-- Preserve both locales --}}
                                <input type="hidden" :name="'blocks['+index+'][content][en][_preserve]'" value="1">
                                <input type="hidden" :name="'blocks['+index+'][content][id][_preserve]'" value="1">

                                <template x-for="loc in ['en', 'id']" :key="loc">
                                    <div x-show="loc === activeTab" class="space-y-4">
                                        <!-- Hero Block -->
                                        <template x-if="block.block_type === 'hero'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Badge Label</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][label]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).label">
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Main Heading</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][heading]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).heading">
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Subheading</label>
                                                    <textarea :name="'blocks['+index+'][content]['+loc+'][subheading]'"
                                                              x-model="content(block, loc).subheading"
                                                              rows="3"
                                                              class="w-full text-xs bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 transition-all resize-y"
                                                              placeholder="Subheading text..."></textarea>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Text Block -->
                                        <template x-if="block.block_type === 'text'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Heading</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][heading]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).heading">
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Body Text</label>
                                                    <textarea :name="'blocks['+index+'][content]['+loc+'][body]'"
                                                              x-model="content(block, loc).body"
                                                              rows="4"
                                                              class="w-full text-xs bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 transition-all resize-y"
                                                              placeholder="Body text..."></textarea>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Stats Block -->
                                        <template x-if="block.block_type === 'stats'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Heading</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][heading]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).heading">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-xs font-semibold text-slate-700">Stat Items</label>
                                                    <template x-for="(item, itemIndex) in items(block, loc)" :key="itemIndex">
                                                        <div class="grid grid-cols-[1fr_1fr_auto] gap-2 items-start bg-white p-2 rounded-lg border border-slate-200">
                                                            <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][label]'" x-model="item.label" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Label">
                                                            <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][value]'" x-model="item.value" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Value">
                                                            <div class="flex items-center gap-1">
                                                                <button type="button" @click="moveItem(block, loc, itemIndex, itemIndex - 1)" :disabled="itemIndex === 0" class="text-[10px] text-slate-400 hover:text-indigo-600 px-1 py-0.5 hover:bg-indigo-50 rounded disabled:opacity-20">&uarr;</button>
                                                                <button type="button" @click="moveItem(block, loc, itemIndex, itemIndex + 1)" class="text-[10px] text-slate-400 hover:text-indigo-600 px-1 py-0.5 hover:bg-indigo-50 rounded">&darr;</button>
                                                                <button type="button" @click="items(block, loc).splice(itemIndex, 1)" class="text-xs text-rose-600 hover:text-rose-700 px-2 py-1 hover:bg-rose-50 rounded font-bold">✕</button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <button type="button" @click="addItem(block, loc, { label: '', value: '' })" class="btn btn-secondary py-1 px-3 text-xs">+ Add Stat</button>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- FAQ Block -->
                                        <template x-if="block.block_type === 'faq'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Heading</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][heading]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).heading">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-xs font-semibold text-slate-700">FAQ Items</label>
                                                    <template x-for="(item, itemIndex) in items(block, loc)" :key="itemIndex">
                                                        <div class="space-y-2 rounded-lg border border-slate-200 bg-white p-3">
                                                            <div class="flex items-center justify-between gap-2">
                                                                <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][question]'" x-model="item.question" class="text-xs flex-1 bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Question">
                                                                <div class="flex items-center gap-1">
                                                                    <button type="button" @click="moveItem(block, loc, itemIndex, itemIndex - 1)" :disabled="itemIndex === 0" class="text-[10px] text-slate-400 hover:text-indigo-600 px-1 py-0.5 hover:bg-indigo-50 rounded disabled:opacity-20">&uarr;</button>
                                                                    <button type="button" @click="moveItem(block, loc, itemIndex, itemIndex + 1)" class="text-[10px] text-slate-400 hover:text-indigo-600 px-1 py-0.5 hover:bg-indigo-50 rounded">&darr;</button>
                                                                    <button type="button" @click="items(block, loc).splice(itemIndex, 1)" class="text-xs text-rose-600 hover:text-rose-700 px-2 py-1 hover:bg-rose-50 rounded font-bold">✕</button>
                                                                </div>
                                                            </div>
                                                            <textarea :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][answer]'"
                                                                      x-model="item.answer"
                                                                      rows="3"
                                                                      class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 transition-all resize-y"
                                                                      placeholder="Answer..."></textarea>
                                                        </div>
                                                    </template>
                                                    <button type="button" @click="addItem(block, loc, { question: '', answer: '' })" class="btn btn-secondary py-1 px-3 text-xs">+ Add FAQ</button>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- CTA Block -->
                                        <template x-if="block.block_type === 'cta'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Heading</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][heading]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).heading">
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Body</label>
                                                    <textarea :name="'blocks['+index+'][content]['+loc+'][body]'"
                                                              x-model="content(block, loc).body"
                                                              rows="3"
                                                              class="w-full text-xs bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 transition-all resize-y"
                                                              placeholder="Body text..."></textarea>
                                                </div>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-xs font-semibold text-slate-700">Button Text</label>
                                                        <input type="text" :name="'blocks['+index+'][content]['+loc+'][button_text]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).button_text">
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-semibold text-slate-700">Button URL</label>
                                                        <input type="text" :name="'blocks['+index+'][content]['+loc+'][button_url]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).button_url">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Process Steps Block -->
                                        <template x-if="block.block_type === 'process_steps'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Section Heading</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][heading]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).heading">
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Section Subtitle</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][subtitle]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).subtitle">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-xs font-semibold text-slate-700">Steps</label>
                                                    <template x-for="(item, itemIndex) in items(block, loc)" :key="itemIndex">
                                                        <div class="space-y-2 rounded-lg border border-slate-200 bg-white p-3">
                                                            <div class="grid grid-cols-2 gap-2">
                                                                <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][step]'" x-model="item.step" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Step #">
                                                                <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][title]'" x-model="item.title" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Title">
                                                            </div>
                                                            <input type="url" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][image]'" x-model="item.image" class="text-xs w-full bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Image URL">
                                                            <div class="flex items-center gap-2">
                                                                <input type="file" accept="image/*" class="text-xs" @change="uploadImage($event, block, loc, item)">
                                                                <span x-show="item.image" class="text-[10px] text-slate-400 ml-auto">URL set</span>
                                                            </div>
                                                            <div x-show="item.image" class="w-20 h-20 rounded-lg overflow-hidden bg-slate-100">
                                                                <img :src="item.image" class="w-full h-full object-cover" alt="Step preview">
                                                            </div>
                                                            <textarea :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][description]'"
                                                                      x-model="item.description"
                                                                      rows="3"
                                                                      class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 transition-all resize-y"
                                                                      placeholder="Description..."></textarea>
                                                            <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][details]'" x-model="item.details" class="text-xs w-full bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Details (comma-separated)">
                                                            <div class="flex items-center justify-between pt-1">
                                                                <div class="flex gap-1">
                                                                    <button type="button" @click="moveItem(block, loc, itemIndex, itemIndex - 1)" :disabled="itemIndex === 0" class="text-[10px] text-slate-500 hover:text-indigo-600 px-1.5 py-0.5 hover:bg-indigo-50 rounded disabled:opacity-30">&uarr; Up</button>
                                                                    <button type="button" @click="moveItem(block, loc, itemIndex, itemIndex + 1)" class="text-[10px] text-slate-500 hover:text-indigo-600 px-1.5 py-0.5 hover:bg-indigo-50 rounded">&darr; Down</button>
                                                                </div>
                                                                <button type="button" @click="items(block, loc).splice(itemIndex, 1)" class="text-xs text-rose-600 hover:text-rose-700 font-bold">Remove Step</button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <button type="button" @click="addItem(block, loc, { step: '', title: '', image: '', description: '', details: '' })" class="btn btn-secondary py-1 px-3 text-xs">+ Add Step</button>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Text+Stats Block -->
                                        <template x-if="block.block_type === 'text_with_stats'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Heading</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][heading]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).heading">
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Body (Paragraph 1)</label>
                                                    <textarea :name="'blocks['+index+'][content]['+loc+'][body]'"
                                                              x-model="content(block, loc).body"
                                                              rows="3"
                                                              class="w-full text-xs bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 transition-all resize-y"
                                                              placeholder="Paragraph 1..."></textarea>
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Body (Paragraph 2)</label>
                                                    <textarea :name="'blocks['+index+'][content]['+loc+'][body2]'"
                                                              x-model="content(block, loc).body2"
                                                              rows="3"
                                                              class="w-full text-xs bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 transition-all resize-y"
                                                              placeholder="Paragraph 2..."></textarea>
                                                </div>
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Checklist Items (comma-separated)</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][checklist]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).checklist">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-xs font-semibold text-slate-700">Stat Items</label>
                                                    <template x-for="(item, itemIndex) in items(block, loc)" :key="itemIndex">
                                                        <div class="grid grid-cols-[1fr_1fr_auto] gap-2 items-start bg-white p-2 rounded-lg border border-slate-200">
                                                            <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][label]'" x-model="item.label" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Label">
                                                            <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][value]'" x-model="item.value" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Value">
                                                            <div class="flex items-center gap-1">
                                                                <button type="button" @click="moveItem(block, loc, itemIndex, itemIndex - 1)" :disabled="itemIndex === 0" class="text-[10px] text-slate-400 hover:text-indigo-600 px-1 py-0.5 hover:bg-indigo-50 rounded disabled:opacity-20">&uarr;</button>
                                                                <button type="button" @click="moveItem(block, loc, itemIndex, itemIndex + 1)" class="text-[10px] text-slate-400 hover:text-indigo-600 px-1 py-0.5 hover:bg-indigo-50 rounded">&darr;</button>
                                                                <button type="button" @click="items(block, loc).splice(itemIndex, 1)" class="text-xs text-rose-600 hover:text-rose-700 px-2 py-1 hover:bg-rose-50 rounded font-bold">✕</button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <button type="button" @click="addItem(block, loc, { label: '', value: '' })" class="btn btn-secondary py-1 px-3 text-xs">+ Add Stat</button>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Articles Block -->
                                        <template x-if="block.block_type === 'articles'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Section Heading</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][heading]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).heading">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-xs font-semibold text-slate-700">Articles</label>
                                                    <template x-for="(item, itemIndex) in items(block, loc)" :key="itemIndex">
                                                        <div class="space-y-2 rounded-lg border border-slate-200 bg-white p-3">
                                                            <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][title]'" x-model="item.title" class="text-xs w-full bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Title">
                                                            <div class="grid grid-cols-2 gap-2">
                                                                <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][category]'" x-model="item.category" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Category">
                                                                <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][date]'" x-model="item.date" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Date">
                                                            </div>
                                                            <input type="url" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][image]'" x-model="item.image" class="text-xs w-full bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Image URL">
                                                            <textarea :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][excerpt]'"
                                                                      x-model="item.excerpt"
                                                                      rows="3"
                                                                      class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 transition-all resize-y"
                                                                      placeholder="Excerpt..."></textarea>
                                                            <button type="button" @click="items(block, loc).splice(itemIndex, 1)" class="text-xs text-rose-600 hover:text-rose-700 font-bold">Remove Article</button>
                                                        </div>
                                                    </template>
                                                    <button type="button" @click="addItem(block, loc, { title: '', category: '', date: '', image: '', excerpt: '' })" class="btn btn-secondary py-1 px-3 text-xs">+ Add Article</button>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Testimonials Block -->
                                        <template x-if="block.block_type === 'testimonials'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-semibold text-slate-700">Section Heading</label>
                                                    <input type="text" :name="'blocks['+index+'][content]['+loc+'][heading]'" class="text-xs w-full bg-white border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" x-model="content(block, loc).heading">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-xs font-semibold text-slate-700">Testimonials</label>
                                                    <template x-for="(item, itemIndex) in items(block, loc)" :key="itemIndex">
                                                        <div class="space-y-2 rounded-lg border border-slate-200 bg-white p-3">
                                                            <div class="grid grid-cols-2 gap-2">
                                                                <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][name]'" x-model="item.name" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Name">
                                                                <input type="text" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][company]'" x-model="item.company" class="text-xs bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Company">
                                                            </div>
                                                            <input type="number" :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][rating]'" x-model="item.rating" class="text-xs w-full bg-slate-50 border border-slate-200 rounded p-1.5" placeholder="Rating (1-5)" min="1" max="5">
                                                            <textarea :name="'blocks['+index+'][content]['+loc+'][items]['+itemIndex+'][content]'"
                                                                      x-model="item.content"
                                                                      rows="3"
                                                                      class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 transition-all resize-y"
                                                                      placeholder="Testimonial content..."></textarea>
                                                            <button type="button" @click="items(block, loc).splice(itemIndex, 1)" class="text-xs text-rose-600 hover:text-rose-700 font-bold">Remove Testimonial</button>
                                                        </div>
                                                    </template>
                                                    <button type="button" @click="addItem(block, loc, { name: '', company: '', rating: 5, content: '' })" class="btn btn-secondary py-1 px-3 text-xs">+ Add Testimonial</button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="space-y-6">
            <!-- Save / Publish Card -->
            <div class="card-modern">
                <h3 class="text-base font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-100">Publish Settings</h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ $page->is_published ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <span class="text-xs font-semibold text-slate-700">Published to Live Site</span>
                    </label>

                    <button type="submit" class="btn btn-primary w-full py-2.5 text-xs font-semibold shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Page Content (EN + ID)
                    </button>
                </div>
            </div>

            <!-- Version History Sidebar Card -->
            @if ($page->versions->isNotEmpty())
            <div class="card-modern">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-900">Version History</h3>
                    <span class="text-xs font-mono text-slate-500">{{ count($page->versions) }} saved</span>
                </div>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                    @foreach ($page->versions as $version)
                    <div class="p-3 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-xs text-slate-800">Version #{{ $version->version_number }}</p>
                            <p class="text-[11px] text-slate-400 font-mono">{{ $version->created_at->diffForHumans() }}</p>
                        </div>
                        <form action="{{ route('admin.content.restoreVersion', ['page' => $page, 'pageVersion' => $version]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary py-1 px-2 text-[11px]">Restore</button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
    function blockEditor(initialBlocks) {
        return {
            activeTab: 'en',
            blocks: initialBlocks.map(b => {
                let content = b.content;
                if (typeof content === 'string') {
                    try {
                        content = JSON.parse(content);
                        if (typeof content === 'string') {
                            content = JSON.parse(content);
                        }
                    } catch (e) {
                        content = {};
                    }
                }
                return {
                    ...b,
                    id: b.id || Date.now(),
                    content: content || { en: {}, id: {} }
                };
            }),
            addBlock(type) {
                this.blocks.push({
                    id: Date.now(),
                    block_type: type,
                    order: this.blocks.length,
                    content: { en: {}, id: {} },
                    is_visible: true
                });
            },
            label(block) {
                const labels = {
                    hero: 'Hero', text: 'Text', stats: 'Stats', faq: 'FAQ',
                    cta: 'CTA', process_steps: 'Process Steps', text_with_stats: 'Text + Stats',
                    articles: 'Articles', testimonials: 'Testimonials'
                };
                return labels[block.block_type] || block.block_type;
            },
            summary(block) {
                const en = this.content(block, 'en') || {};
                if (block.block_type === 'hero') return en.label || '';
                if (block.block_type === 'text') return en.heading || '';
                if (block.block_type === 'cta') return en.heading || '';
                if (block.block_type === 'stats') return (en.items || []).length + ' stats';
                if (block.block_type === 'faq') return (en.items || []).length + ' items';
                if (block.block_type === 'process_steps') return (en.items || []).length + ' steps';
                if (block.block_type === 'text_with_stats') return en.heading || '';
                if (block.block_type === 'articles') return (en.items || []).length + ' articles';
                if (block.block_type === 'testimonials') return (en.items || []).length + ' testimonials';
                return '';
            },
            duplicateBlock(index) {
                const clone = JSON.parse(JSON.stringify(this.blocks[index]));
                clone.id = Date.now();
                clone.order = this.blocks.length;
                this.blocks.splice(index + 1, 0, clone);
            },
            removeBlock(index) {
                if (!confirm('Remove this block?')) return;
                this.blocks.splice(index, 1);
            },
            content(block, locale) {
                if (typeof block.content === 'string') {
                    try {
                        block.content = JSON.parse(block.content);
                        if (typeof block.content === 'string') {
                            block.content = JSON.parse(block.content);
                        }
                    } catch (e) {
                        block.content = {};
                    }
                }
                block.content ??= {};
                block.content[locale] ??= {};
                return block.content[locale];
            },
items(block, locale) {
                const c = this.content(block, locale);
                if (typeof c.items === 'string') {
                    try {
                        c.items = JSON.parse(c.items || '[]');
                    } catch (e) {
                        c.items = [];
                    }
                }
                c.items ??= [];
                return c.items;
            },
            addItem(block, locale, item) {
                this.items(block, locale).push(item);
            },
            moveItem(block, locale, from, to) {
                const items = this.items(block, locale);
                if (to < 0 || to >= items.length) return;
                const moved = items.splice(from, 1)[0];
                items.splice(to, 0, moved);
            },
            async uploadImage(event, block, locale, item) {
                const file = event.target.files[0];
                if (!file) return;
                const form = new FormData();
                form.append('file', file);
                try {
                    const res = await fetch('{{ route('admin.media.upload') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: form,
                    });
                    const data = await res.json();
                    if (data.url) {
                        item.image = data.url;
                    }
                } catch (e) {
                    alert('Upload failed');
                }
            },
            init() {
                const list = this.$refs.blockList;
                new Sortable(list, {
                    handle: '.cursor-move',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: (evt) => {
                        const moved = this.blocks.splice(evt.oldIndex, 1)[0];
                        this.blocks.splice(evt.newIndex, 0, moved);
                    }
                });
            }
        }
    }
</script>
@endpush
@endsection