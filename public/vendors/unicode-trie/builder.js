// Generated by CoffeeScript 1.7.1
var UnicodeTrie, UnicodeTrieBuilder, pako;

UnicodeTrie = require('./');

pako = require('pako');

UnicodeTrieBuilder = (function() {
  var BAD_UTF8_DATA_OFFSET, CP_PER_INDEX_1_ENTRY, DATA_0800_OFFSET, DATA_BLOCK_LENGTH, DATA_GRANULARITY, DATA_MASK, DATA_NULL_OFFSET, DATA_START_OFFSET, INDEX_1_LENGTH, INDEX_1_OFFSET, INDEX_2_BLOCK_LENGTH, INDEX_2_BMP_LENGTH, INDEX_2_MASK, INDEX_2_NULL_OFFSET, INDEX_2_OFFSET, INDEX_2_START_OFFSET, INDEX_GAP_LENGTH, INDEX_GAP_OFFSET, INDEX_SHIFT, INITIAL_DATA_LENGTH, LSCP_INDEX_2_LENGTH, LSCP_INDEX_2_OFFSET, MAX_DATA_LENGTH, MAX_INDEX_1_LENGTH, MAX_INDEX_2_LENGTH, MAX_INDEX_LENGTH, MEDIUM_DATA_LENGTH, NEW_DATA_START_OFFSET, OMITTED_BMP_INDEX_1_LENGTH, SHIFT_1, SHIFT_1_2, SHIFT_2, UTF8_2B_INDEX_2_LENGTH, UTF8_2B_INDEX_2_OFFSET, equal_int;

  SHIFT_1 = 6 + 5;

  SHIFT_2 = 5;

  SHIFT_1_2 = SHIFT_1 - SHIFT_2;

  OMITTED_BMP_INDEX_1_LENGTH = 0x10000 >> SHIFT_1;

  CP_PER_INDEX_1_ENTRY = 1 << SHIFT_1;

  INDEX_2_BLOCK_LENGTH = 1 << SHIFT_1_2;

  INDEX_2_MASK = INDEX_2_BLOCK_LENGTH - 1;

  DATA_BLOCK_LENGTH = 1 << SHIFT_2;

  DATA_MASK = DATA_BLOCK_LENGTH - 1;

  INDEX_SHIFT = 2;

  DATA_GRANULARITY = 1 << INDEX_SHIFT;

  INDEX_2_OFFSET = 0;

  LSCP_INDEX_2_OFFSET = 0x10000 >> SHIFT_2;

  LSCP_INDEX_2_LENGTH = 0x400 >> SHIFT_2;

  INDEX_2_BMP_LENGTH = LSCP_INDEX_2_OFFSET + LSCP_INDEX_2_LENGTH;

  UTF8_2B_INDEX_2_OFFSET = INDEX_2_BMP_LENGTH;

  UTF8_2B_INDEX_2_LENGTH = 0x800 >> 6;

  INDEX_1_OFFSET = UTF8_2B_INDEX_2_OFFSET + UTF8_2B_INDEX_2_LENGTH;

  MAX_INDEX_1_LENGTH = 0x100000 >> SHIFT_1;

  BAD_UTF8_DATA_OFFSET = 0x80;

  DATA_START_OFFSET = 0xc0;

  DATA_NULL_OFFSET = DATA_START_OFFSET;

  NEW_DATA_START_OFFSET = DATA_NULL_OFFSET + 0x40;

  DATA_0800_OFFSET = NEW_DATA_START_OFFSET + 0x780;

  INITIAL_DATA_LENGTH = 1 << 14;

  MEDIUM_DATA_LENGTH = 1 << 17;

  MAX_DATA_LENGTH = 0xffff << INDEX_SHIFT;

  INDEX_1_LENGTH = 0x110000 >> SHIFT_1;

  MAX_DATA_LENGTH = 0x110000 + 0x40 + 0x40 + 0x400;

  INDEX_GAP_OFFSET = INDEX_2_BMP_LENGTH;

  INDEX_GAP_LENGTH = ((UTF8_2B_INDEX_2_LENGTH + MAX_INDEX_1_LENGTH) + INDEX_2_MASK) & ~INDEX_2_MASK;

  MAX_INDEX_2_LENGTH = (0x110000 >> SHIFT_2) + LSCP_INDEX_2_LENGTH + INDEX_GAP_LENGTH + INDEX_2_BLOCK_LENGTH;

  INDEX_2_NULL_OFFSET = INDEX_GAP_OFFSET + INDEX_GAP_LENGTH;

  INDEX_2_START_OFFSET = INDEX_2_NULL_OFFSET + INDEX_2_BLOCK_LENGTH;

  MAX_INDEX_LENGTH = 0xffff;

  function UnicodeTrieBuilder(initialValue, errorValue) {
    var i, j, _i, _j, _k, _l, _m, _n, _o, _p, _q, _r, _ref, _s, _t;
    this.initialValue = initialValue != null ? initialValue : 0;
    this.errorValue = errorValue != null ? errorValue : 0;
    this.index1 = new Int32Array(INDEX_1_LENGTH);
    this.index2 = new Int32Array(MAX_INDEX_2_LENGTH);
    this.highStart = 0x110000;
    this.data = new Uint32Array(INITIAL_DATA_LENGTH);
    this.dataCapacity = INITIAL_DATA_LENGTH;
    this.firstFreeBlock = 0;
    this.isCompacted = false;
    this.map = new Int32Array(MAX_DATA_LENGTH >> SHIFT_2);
    for (i = _i = 0; _i < 0x80; i = _i += 1) {
      this.data[i] = this.initialValue;
    }
    for (i = _j = i; _j < 0xc0; i = _j += 1) {
      this.data[i] = this.errorValue;
    }
    for (i = _k = DATA_NULL_OFFSET; _k < NEW_DATA_START_OFFSET; i = _k += 1) {
      this.data[i] = this.initialValue;
    }
    this.dataNullOffset = DATA_NULL_OFFSET;
    this.dataLength = NEW_DATA_START_OFFSET;
    i = 0;
    for (j = _l = 0; _l < 128; j = _l += DATA_BLOCK_LENGTH) {
      this.index2[i] = j;
      this.map[i++] = 1;
    }
    for (j = _m = j; DATA_BLOCK_LENGTH > 0 ? _m < 0xc0 : _m > 0xc0; j = _m += DATA_BLOCK_LENGTH) {
      this.map[i++] = 0;
    }
    this.map[i++] = (0x110000 >> SHIFT_2) - (0x80 >> SHIFT_2) + 1 + LSCP_INDEX_2_LENGTH;
    j += DATA_BLOCK_LENGTH;
    for (j = _n = j; DATA_BLOCK_LENGTH > 0 ? _n < NEW_DATA_START_OFFSET : _n > NEW_DATA_START_OFFSET; j = _n += DATA_BLOCK_LENGTH) {
      this.map[i++] = 0;
    }
    for (i = _o = _ref = 0x80 >> SHIFT_2; _o < INDEX_2_BMP_LENGTH; i = _o += 1) {
      this.index2[i] = DATA_NULL_OFFSET;
    }
    for (i = _p = 0; _p < INDEX_GAP_LENGTH; i = _p += 1) {
      this.index2[INDEX_GAP_OFFSET + i] = -1;
    }
    for (i = _q = 0; _q < INDEX_2_BLOCK_LENGTH; i = _q += 1) {
      this.index2[INDEX_2_NULL_OFFSET + i] = DATA_NULL_OFFSET;
    }
    this.index2NullOffset = INDEX_2_NULL_OFFSET;
    this.index2Length = INDEX_2_START_OFFSET;
    j = 0;
    for (i = _r = 0; _r < OMITTED_BMP_INDEX_1_LENGTH; i = _r += 1) {
      this.index1[i] = j;
      j += INDEX_2_BLOCK_LENGTH;
    }
    for (i = _s = i; _s < INDEX_1_LENGTH; i = _s += 1) {
      this.index1[i] = INDEX_2_NULL_OFFSET;
    }
    for (i = _t = 0x80; _t < 2048; i = _t += DATA_BLOCK_LENGTH) {
      this.set(i, this.initialValue);
    }
    return;
  }

  UnicodeTrieBuilder.prototype.set = function(codePoint, value) {
    var block;
    if (codePoint < 0 || codePoint > 0x10ffff) {
      throw new Error('Invalid code point');
    }
    if (this.isCompacted) {
      throw new Error('Already compacted');
    }
    block = this._getDataBlock(codePoint, true);
    this.data[block + (codePoint & DATA_MASK)] = value;
    return this;
  };

  UnicodeTrieBuilder.prototype.setRange = function(start, end, value, overwrite) {
    var block, i2, limit, nextStart, repeatBlock, rest, setRepeatBlock;
    if (overwrite == null) {
      overwrite = true;
    }
    if (start > 0x10ffff || end > 0x10ffff || start > end) {
      throw new Error('Invalid code point');
    }
    if (this.isCompacted) {
      throw new Error('Already compacted');
    }
    if (!overwrite && value === this.initialValue) {
      return this;
    }
    limit = end + 1;
    if ((start & DATA_MASK) !== 0) {
      block = this._getDataBlock(start, true);
      nextStart = (start + DATA_BLOCK_LENGTH) & ~DATA_MASK;
      if (nextStart <= limit) {
        this._fillBlock(block, start & DATA_MASK, DATA_BLOCK_LENGTH, value, this.initialValue, overwrite);
        start = nextStart;
      } else {
        this._fillBlock(block, start & DATA_MASK, limit & DATA_MASK, value, this.initialValue, overwrite);
        return this;
      }
    }
    rest = limit & DATA_MASK;
    limit &= ~DATA_MASK;
    if (value === this.initialValue) {
      repeatBlock = this.dataNullOffset;
    } else {
      repeatBlock = -1;
    }
    while (start < limit) {
      setRepeatBlock = false;
      if (value === this.initialValue && this._isInNullBlock(start, true)) {
        start += DATA_BLOCK_LENGTH;
        continue;
      }
      i2 = this._getIndex2Block(start, true);
      i2 += (start >> SHIFT_2) & INDEX_2_MASK;
      block = this.index2[i2];
      if (this._isWritableBlock(block)) {
        if (overwrite && block >= DATA_0800_OFFSET) {
          setRepeatBlock = true;
        } else {
          this._fillBlock(block, 0, DATA_BLOCK_LENGTH, value, this.initialValue, overwrite);
        }
      } else if (this.data[block] !== value && (overwrite || block === this.dataNullOffset)) {
        setRepeatBlock = true;
      }
      if (setRepeatBlock) {
        if (repeatBlock >= 0) {
          this._setIndex2Entry(i2, repeatBlock);
        } else {
          repeatBlock = this._getDataBlock(start, true);
          this._writeBlock(repeatBlock, value);
        }
      }
      start += DATA_BLOCK_LENGTH;
    }
    if (rest > 0) {
      block = this._getDataBlock(start, true);
      this._fillBlock(block, 0, rest, value, this.initialValue, overwrite);
    }
    return this;
  };

  UnicodeTrieBuilder.prototype.get = function(c, fromLSCP) {
    var block, i2;
    if (fromLSCP == null) {
      fromLSCP = true;
    }
    if (c < 0 || c > 0x10ffff) {
      return this.errorValue;
    }
    if (c >= this.highStart && (!(c >= 0xd800 && c < 0xdc00) || fromLSCP)) {
      return this.data[this.dataLength - DATA_GRANULARITY];
    }
    if ((c >= 0xd800 && c < 0xdc00) && fromLSCP) {
      i2 = (LSCP_INDEX_2_OFFSET - (0xd800 >> SHIFT_2)) + (c >> SHIFT_2);
    } else {
      i2 = this.index1[c >> SHIFT_1] + ((c >> SHIFT_2) & INDEX_2_MASK);
    }
    block = this.index2[i2];
    return this.data[block + (c & DATA_MASK)];
  };

  UnicodeTrieBuilder.prototype._isInNullBlock = function(c, forLSCP) {
    var block, i2;
    if ((c & 0xfffffc00) === 0xd800 && forLSCP) {
      i2 = LSCP_INDEX_2_OFFSET - (0xd800 >> SHIFT_2) + (c >> SHIFT_2);
    } else {
      i2 = this.index1[c >> SHIFT_1] + ((c >> SHIFT_2) & INDEX_2_MASK);
    }
    block = this.index2[i2];
    return block === this.dataNullOffset;
  };

  UnicodeTrieBuilder.prototype._allocIndex2Block = function() {
    var newBlock, newTop;
    newBlock = this.index2Length;
    newTop = newBlock + INDEX_2_BLOCK_LENGTH;
    if (newTop > this.index2.length) {
      throw new Error("Internal error in Trie2 creation.");
    }
    this.index2Length = newTop;
    this.index2.set(this.index2.subarray(this.index2NullOffset, this.index2NullOffset + INDEX_2_BLOCK_LENGTH), newBlock);
    return newBlock;
  };

  UnicodeTrieBuilder.prototype._getIndex2Block = function(c, forLSCP) {
    var i1, i2;
    if (c >= 0xd800 && c < 0xdc00 && forLSCP) {
      return LSCP_INDEX_2_OFFSET;
    }
    i1 = c >> SHIFT_1;
    i2 = this.index1[i1];
    if (i2 === this.index2NullOffset) {
      i2 = this._allocIndex2Block();
      this.index1[i1] = i2;
    }
    return i2;
  };

  UnicodeTrieBuilder.prototype._isWritableBlock = function(block) {
    return block !== this.dataNullOffset && this.map[block >> SHIFT_2] === 1;
  };

  UnicodeTrieBuilder.prototype._allocDataBlock = function(copyBlock) {
    var capacity, newBlock, newData, newTop;
    if (this.firstFreeBlock !== 0) {
      newBlock = this.firstFreeBlock;
      this.firstFreeBlock = -this.map[newBlock >> SHIFT_2];
    } else {
      newBlock = this.dataLength;
      newTop = newBlock + DATA_BLOCK_LENGTH;
      if (newTop > this.dataCapacity) {
        if (this.dataCapacity < MEDIUM_DATA_LENGTH) {
          capacity = MEDIUM_DATA_LENGTH;
        } else if (this.dataCapacity < MAX_DATA_LENGTH) {
          capacity = MAX_DATA_LENGTH;
        } else {
          throw new Error("Internal error in Trie2 creation.");
        }
        newData = new Uint32Array(capacity);
        newData.set(this.data.subarray(0, this.dataLength));
        this.data = newData;
        this.dataCapacity = capacity;
      }
      this.dataLength = newTop;
    }
    this.data.set(this.data.subarray(copyBlock, copyBlock + DATA_BLOCK_LENGTH), newBlock);
    this.map[newBlock >> SHIFT_2] = 0;
    return newBlock;
  };

  UnicodeTrieBuilder.prototype._releaseDataBlock = function(block) {
    this.map[block >> SHIFT_2] = -this.firstFreeBlock;
    return this.firstFreeBlock = block;
  };

  UnicodeTrieBuilder.prototype._setIndex2Entry = function(i2, block) {
    var oldBlock;
    ++this.map[block >> SHIFT_2];
    oldBlock = this.index2[i2];
    if (--this.map[oldBlock >> SHIFT_2] === 0) {
      this._releaseDataBlock(oldBlock);
    }
    return this.index2[i2] = block;
  };

  UnicodeTrieBuilder.prototype._getDataBlock = function(c, forLSCP) {
    var i2, newBlock, oldBlock;
    i2 = this._getIndex2Block(c, forLSCP);
    i2 += (c >> SHIFT_2) & INDEX_2_MASK;
    oldBlock = this.index2[i2];
    if (this._isWritableBlock(oldBlock)) {
      return oldBlock;
    }
    newBlock = this._allocDataBlock(oldBlock);
    this._setIndex2Entry(i2, newBlock);
    return newBlock;
  };

  UnicodeTrieBuilder.prototype._fillBlock = function(block, start, limit, value, initialValue, overwrite) {
    var i, _i, _j, _ref, _ref1, _ref2, _ref3;
    if (overwrite) {
      for (i = _i = _ref = block + start, _ref1 = block + limit; _i < _ref1; i = _i += 1) {
        this.data[i] = value;
      }
    } else {
      for (i = _j = _ref2 = block + start, _ref3 = block + limit; _j < _ref3; i = _j += 1) {
        if (this.data[i] === initialValue) {
          this.data[i] = value;
        }
      }
    }
  };

  UnicodeTrieBuilder.prototype._writeBlock = function(block, value) {
    var limit;
    limit = block + DATA_BLOCK_LENGTH;
    while (block < limit) {
      this.data[block++] = value;
    }
  };

  UnicodeTrieBuilder.prototype._findHighStart = function(highValue) {
    var block, c, data32, i1, i2, i2Block, index2NullOffset, initialValue, j, nullBlock, prev, prevBlock, prevI2Block, value;
    data32 = this.data;
    initialValue = this.initialValue;
    index2NullOffset = this.index2NullOffset;
    nullBlock = this.dataNullOffset;
    if (highValue === initialValue) {
      prevI2Block = index2NullOffset;
      prevBlock = nullBlock;
    } else {
      prevI2Block = -1;
      prevBlock = -1;
    }
    prev = 0x110000;
    i1 = INDEX_1_LENGTH;
    c = prev;
    while (c > 0) {
      i2Block = this.index1[--i1];
      if (i2Block === prevI2Block) {
        c -= CP_PER_INDEX_1_ENTRY;
        continue;
      }
      prevI2Block = i2Block;
      if (i2Block === index2NullOffset) {
        if (highValue !== initialValue) {
          return c;
        }
        c -= CP_PER_INDEX_1_ENTRY;
      } else {
        i2 = INDEX_2_BLOCK_LENGTH;
        while (i2 > 0) {
          block = this.index2[i2Block + --i2];
          if (block === prevBlock) {
            c -= DATA_BLOCK_LENGTH;
            continue;
          }
          prevBlock = block;
          if (block === nullBlock) {
            if (highValue !== initialValue) {
              return c;
            }
            c -= DATA_BLOCK_LENGTH;
          } else {
            j = DATA_BLOCK_LENGTH;
            while (j > 0) {
              value = data32[block + --j];
              if (value !== highValue) {
                return c;
              }
              --c;
            }
          }
        }
      }
    }
    return 0;
  };

  equal_int = function(a, s, t, length) {
    var i, _i;
    for (i = _i = 0; _i < length; i = _i += 1) {
      if (a[s + i] !== a[t + i]) {
        return false;
      }
    }
    return true;
  };

  UnicodeTrieBuilder.prototype._findSameDataBlock = function(dataLength, otherBlock, blockLength) {
    var block;
    dataLength -= blockLength;
    block = 0;
    while (block <= dataLength) {
      if (equal_int(this.data, block, otherBlock, blockLength)) {
        return block;
      }
      block += DATA_GRANULARITY;
    }
    return -1;
  };

  UnicodeTrieBuilder.prototype._findSameIndex2Block = function(index2Length, otherBlock) {
    var block, _i;
    index2Length -= INDEX_2_BLOCK_LENGTH;
    for (block = _i = 0; _i <= index2Length; block = _i += 1) {
      if (equal_int(this.index2, block, otherBlock, INDEX_2_BLOCK_LENGTH)) {
        return block;
      }
    }
    return -1;
  };

  UnicodeTrieBuilder.prototype._compactData = function() {
    var blockCount, blockLength, i, mapIndex, movedStart, newStart, overlap, start, _i, _j, _k, _l, _ref;
    newStart = DATA_START_OFFSET;
    start = 0;
    i = 0;
    while (start < newStart) {
      this.map[i++] = start;
      start += DATA_BLOCK_LENGTH;
    }
    blockLength = 64;
    blockCount = blockLength >> SHIFT_2;
    start = newStart;
    while (start < this.dataLength) {
      if (start === DATA_0800_OFFSET) {
        blockLength = DATA_BLOCK_LENGTH;
        blockCount = 1;
      }
      if (this.map[start >> SHIFT_2] <= 0) {
        start += blockLength;
        continue;
      }
      if ((movedStart = this._findSameDataBlock(newStart, start, blockLength)) >= 0) {
        mapIndex = start >> SHIFT_2;
        for (i = _i = blockCount; _i > 0; i = _i += -1) {
          this.map[mapIndex++] = movedStart;
          movedStart += DATA_BLOCK_LENGTH;
        }
        start += blockLength;
        continue;
      }
      overlap = blockLength - DATA_GRANULARITY;
      while (overlap > 0 && !equal_int(this.data, newStart - overlap, start, overlap)) {
        overlap -= DATA_GRANULARITY;
      }
      if (overlap > 0 || newStart < start) {
        movedStart = newStart - overlap;
        mapIndex = start >> SHIFT_2;
        for (i = _j = blockCount; _j > 0; i = _j += -1) {
          this.map[mapIndex++] = movedStart;
          movedStart += DATA_BLOCK_LENGTH;
        }
        start += overlap;
        for (i = _k = _ref = blockLength - overlap; _k > 0; i = _k += -1) {
          this.data[newStart++] = this.data[start++];
        }
      } else {
        mapIndex = start >> SHIFT_2;
        for (i = _l = blockCount; _l > 0; i = _l += -1) {
          this.map[mapIndex++] = start;
          start += DATA_BLOCK_LENGTH;
        }
        newStart = start;
      }
    }
    i = 0;
    while (i < this.index2Length) {
      if (i === INDEX_GAP_OFFSET) {
        i += INDEX_GAP_LENGTH;
      }
      this.index2[i] = this.map[this.index2[i] >> SHIFT_2];
      ++i;
    }
    this.dataNullOffset = this.map[this.dataNullOffset >> SHIFT_2];
    while ((newStart & (DATA_GRANULARITY - 1)) !== 0) {
      this.data[newStart++] = this.initialValue;
    }
    this.dataLength = newStart;
  };

  UnicodeTrieBuilder.prototype._compactIndex2 = function() {
    var i, movedStart, newStart, overlap, start, _i, _j, _ref;
    newStart = INDEX_2_BMP_LENGTH;
    start = 0;
    i = 0;
    while (start < newStart) {
      this.map[i++] = start;
      start += INDEX_2_BLOCK_LENGTH;
    }
    newStart += UTF8_2B_INDEX_2_LENGTH + ((this.highStart - 0x10000) >> SHIFT_1);
    start = INDEX_2_NULL_OFFSET;
    while (start < this.index2Length) {
      if ((movedStart = this._findSameIndex2Block(newStart, start)) >= 0) {
        this.map[start >> SHIFT_1_2] = movedStart;
        start += INDEX_2_BLOCK_LENGTH;
        continue;
      }
      overlap = INDEX_2_BLOCK_LENGTH - 1;
      while (overlap > 0 && !equal_int(this.index2, newStart - overlap, start, overlap)) {
        --overlap;
      }
      if (overlap > 0 || newStart < start) {
        this.map[start >> SHIFT_1_2] = newStart - overlap;
        start += overlap;
        for (i = _i = _ref = INDEX_2_BLOCK_LENGTH - overlap; _i > 0; i = _i += -1) {
          this.index2[newStart++] = this.index2[start++];
        }
      } else {
        this.map[start >> SHIFT_1_2] = start;
        start += INDEX_2_BLOCK_LENGTH;
        newStart = start;
      }
    }
    for (i = _j = 0; _j < INDEX_1_LENGTH; i = _j += 1) {
      this.index1[i] = this.map[this.index1[i] >> SHIFT_1_2];
    }
    this.index2NullOffset = this.map[this.index2NullOffset >> SHIFT_1_2];
    while ((newStart & ((DATA_GRANULARITY - 1) | 1)) !== 0) {
      this.index2[newStart++] = 0x0000ffff << INDEX_SHIFT;
    }
    return this.index2Length = newStart;
  };

  UnicodeTrieBuilder.prototype._compact = function() {
    var highStart, highValue, suppHighStart;
    highValue = this.get(0x10ffff);
    highStart = this._findHighStart(highValue);
    highStart = (highStart + (CP_PER_INDEX_1_ENTRY - 1)) & ~(CP_PER_INDEX_1_ENTRY - 1);
    if (highStart === 0x110000) {
      highValue = this.errorValue;
    }
    this.highStart = highStart;
    if (this.highStart < 0x110000) {
      suppHighStart = this.highStart <= 0x10000 ? 0x10000 : this.highStart;
      this.setRange(suppHighStart, 0x10ffff, this.initialValue, true);
    }
    this._compactData();
    if (this.highStart > 0x10000) {
      this._compactIndex2();
    }
    this.data[this.dataLength++] = highValue;
    while ((this.dataLength & (DATA_GRANULARITY - 1)) !== 0) {
      this.data[this.dataLength++] = this.initialValue;
    }
    return this.isCompacted = true;
  };

  UnicodeTrieBuilder.prototype.freeze = function() {
    var allIndexesLength, data, dataMove, dest, destIdx, i, index1Length, index2Offset, indexLength, _i, _j, _k, _l, _m, _n, _ref, _ref1, _ref2, _ref3;
    if (!this.isCompacted) {
      this._compact();
    }
    if (this.highStart <= 0x10000) {
      allIndexesLength = INDEX_1_OFFSET;
    } else {
      allIndexesLength = this.index2Length;
    }
    dataMove = allIndexesLength;
    if (allIndexesLength > MAX_INDEX_LENGTH || (dataMove + this.dataNullOffset) > 0xffff || (dataMove + DATA_0800_OFFSET) > 0xffff || (dataMove + this.dataLength) > MAX_DATA_LENGTH) {
      throw new Error("Trie data is too large.");
    }
    indexLength = allIndexesLength + this.dataLength;
    data = new Int32Array(indexLength);
    destIdx = 0;
    for (i = _i = 0; _i < INDEX_2_BMP_LENGTH; i = _i += 1) {
      data[destIdx++] = (this.index2[i] + dataMove) >> INDEX_SHIFT;
    }
    for (i = _j = 0, _ref = 0xc2 - 0xc0; _j < _ref; i = _j += 1) {
      data[destIdx++] = dataMove + BAD_UTF8_DATA_OFFSET;
    }
    for (i = _k = i, _ref1 = 0xe0 - 0xc0; _k < _ref1; i = _k += 1) {
      data[destIdx++] = dataMove + this.index2[i << (6 - SHIFT_2)];
    }
    if (this.highStart > 0x10000) {
      index1Length = (this.highStart - 0x10000) >> SHIFT_1;
      index2Offset = INDEX_2_BMP_LENGTH + UTF8_2B_INDEX_2_LENGTH + index1Length;
      for (i = _l = 0; _l < index1Length; i = _l += 1) {
        data[destIdx++] = INDEX_2_OFFSET + this.index1[i + OMITTED_BMP_INDEX_1_LENGTH];
      }
      for (i = _m = 0, _ref2 = this.index2Length - index2Offset; _m < _ref2; i = _m += 1) {
        data[destIdx++] = (dataMove + this.index2[index2Offset + i]) >> INDEX_SHIFT;
      }
    }
    for (i = _n = 0, _ref3 = this.dataLength; _n < _ref3; i = _n += 1) {
      data[destIdx++] = this.data[i];
    }
    dest = new UnicodeTrie({
      data: data,
      highStart: this.highStart,
      errorValue: this.errorValue
    });
    return dest;
  };

  UnicodeTrieBuilder.prototype.toBuffer = function() {
    var b, buf, compressed, data, i, trie, _i, _len;
    trie = this.freeze();
    data = new Uint8Array(trie.data.buffer);
    compressed = pako.deflateRaw(data);
    compressed = pako.deflateRaw(compressed);
    buf = new Buffer(compressed.length + 12);
    buf.writeUInt32BE(trie.highStart, 0);
    buf.writeUInt32BE(trie.errorValue, 4);
    buf.writeUInt32BE(data.length, 8);
    for (i = _i = 0, _len = compressed.length; _i < _len; i = ++_i) {
      b = compressed[i];
      buf[i + 12] = b;
    }
    return buf;
  };

  return UnicodeTrieBuilder;

})();

module.exports = UnicodeTrieBuilder;
