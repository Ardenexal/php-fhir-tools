<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * The `#fragment` links found in narratives during one {@see NarrativeXhtmlChecker::check()} call,
 * and the ids they may point at.
 *
 * A link can only be judged once the whole tree has been walked: its target may be an element id, a
 * contained resource, or an `id`/`name` attribute in a narrative the walk reaches later. So links are
 * recorded as they are found and resolved at the end.
 *
 * Targets are collected across everything the caller passed in, not only the resource that holds the
 * link. That is deliberately the wider search: it can only miss a broken link, never report a working
 * one, and a false error is the outcome the conformance comparison must never move towards.
 *
 * @internal
 */
final class NarrativeLinkTargets
{
    /** @var array<string, true> */
    private array $targets = [];

    /** @var list<array{path: string, href: string, xpath: string, text: string}> */
    private array $links = [];

    public function addTarget(string $id): void
    {
        $this->targets[$id] = true;
    }

    /**
     * @param string $path  the model path of the xhtml property, where the finding is reported
     * @param string $href  the link as written, including the leading `#`
     * @param string $xpath the anchor's element path inside the narrative, e.g. `div/p/a`
     * @param string $text  the anchor's text content
     */
    public function addLink(string $path, string $href, string $xpath, string $text): void
    {
        $this->links[] = ['path' => $path, 'href' => $href, 'xpath' => $xpath, 'text' => $text];
    }

    /**
     * Links whose fragment matches no recorded target, in the order they were found. The same link
     * written twice in one narrative is returned once, as every other narrative finding is.
     *
     * @return list<array{path: string, href: string, xpath: string, text: string}>
     */
    public function unresolved(): array
    {
        $unresolved = [];

        foreach ($this->links as $link) {
            if (isset($this->targets[substr($link['href'], 1)])) {
                continue;
            }

            $unresolved[implode("\0", $link)] = $link;
        }

        return array_values($unresolved);
    }
}
