import hljs from 'highlight.js';
import {php, twig} from 'highlight.js';
// import twig from 'highlight.js/lib/languages/twig';

hljs.registerLanguage('php', php);
hljs.registerLanguage('twig', twig);

hljs.initHighlightingOnLoad();
