import {init} from './api/search-engine.js';

import {fetchCoverM} from "./api/fetch-cover-list.js";

import {fetchCoverL, addToList} from "./api/detail_book_script.js";


init();

fetchCoverM();

fetchCoverL();

addToList();



