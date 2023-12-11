<?php
namespace Potato\Compressor\Model\Optimisation\Processor\Finder\DOM;

use Potato\Compressor\Model\Optimisation\Processor\Finder\ImageInterface;
use Potato\Compressor\Model\Optimisation\Processor\Finder\Result\Tag;

class Image extends AbstractDom implements ImageInterface
{
    protected $needles = array(
        '//img[@src]' //get all img tags with src attribute
    );

    /**
     * @param string $xpath
     * @param string $haystack
     * @param null $start
     * @param null $end
     *
     * @return array
     * @throws \Exception
     */
    public function findByXPath($xpath, $haystack, $start = null, $end = null)
    {
        return $this->findByNeedle($xpath, $haystack, $start, $end);
    }

    /**
     * @param string $source
     * @param int    $pos
     *
     * @return Tag
     * @throws \Exception
     */
    protected function processResult($source, $pos)
    {
        $raw = parent::processResult($source, $pos);
        $result = new Tag(
            $raw->getContent(), $raw->getStart(), $raw->getEnd()
        );
        return $result;
    }
	
	protected function findByNeedle(
        $needle, $haystack, $start = null, $end = null
    ) {
        $needle = strtolower($needle);
        $html = $haystack;
        if (null !== $start || null !== $end) {
            if (null === $start) {
                $start = 0;
            }
            $length = null;
            if (null !== $end) {
                $length = $end - $start;
            }
            $html = mb_substr($haystack, $start, $length);
        }
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);//skip E_WARNING
        $xpath = new \DOMXpath($dom);
        $tagList = $xpath->query($needle);
        $tagListByNodeName = [];
        $regExpListByNodeName = [];
        $result = [];
        foreach ($tagList as $tag) {
            /** @var \DOMNode $tag */
            if (!array_key_exists($tag->nodeName, $tagListByNodeName)) {
                $xpathByNodeName = new \DOMXpath($dom);
                $tagListByNodeName[$tag->nodeName] = $xpathByNodeName->query('//' . $tag->nodeName);
            }
            if (!array_key_exists($tag->nodeName, $regExpListByNodeName)) {
                preg_match_all(
                    '/<' . $tag->nodeName . '\s[^>]*>/is', $html,
                    $matches, PREG_OFFSET_CAPTURE
                );
                $regExpListByNodeName[$tag->nodeName] = $this->excludeMatchesWhichWithinHtmlComment(
                    $matches[0], $html
                );
                //$regExpListByNodeName[$tag->nodeName] = $this->excludeMatchesWhichWithinScriptComment(
                //    $regExpListByNodeName[$tag->nodeName], $html
               // );
            }
            $currentTagList = $tagListByNodeName[$tag->nodeName];
			
            if ($currentTagList->length !== count($regExpListByNodeName[$tag->nodeName])) {
                //if something wrong
                continue;
            }
            $regExpTag = null;
            foreach ($tagListByNodeName[$tag->nodeName] as $key => $nodeTag) {
                if (!$tag->isSameNode($nodeTag)) {
                    continue;
                }
                $regExpTag = $regExpListByNodeName[$tag->nodeName][$key];
                break;
            }
            if (null === $regExpTag) {
                continue;
            }
            $result[] = $this->processResult($regExpTag[0], $regExpTag[1]);
        }
        return $result;
    }
}
