<?php

namespace App;

trait Taggable
{
    public function tagsString($glue = ',')
    {
        return implode($glue, $this->tags->pluck('name')->toArray());
    }

    public function attachTags($tagNames)
    {
        if (! $tagNames) return $this;
        $this->tags()->attach($this->findTagsIds($tagNames));

        return $this;
    }

    public function detachTag($tagName)
    {
        $this->tags()->detach(Tag::whereName(strtolower($tagName))->get());
    }

    public function syncTags($tags)
    {
        $this->clearTags()->attachTags($tags);
    }

    public function clearTags()
    {
        $this->tags->each(function($tag){
            $this->detachTag($tag->name);
        });

        return $this;
    }

    private function findTagsIds($tagNames)
    {
        return collect(is_array($tagNames) ? $tagNames : explode(',', $tagNames))->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => strtolower($tagName)]);
        })->unique('id')->pluck('id');
    }
}
