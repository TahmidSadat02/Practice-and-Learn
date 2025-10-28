from collections import defaultdict
class Solution:
    def groupAnagrams(self, strs):
        groups = defaultdict(list)
        
        for word in strs:
            count = [0]*26
            for char in word:
                count [ord(char) - ord('a')] += 1
            groups[tuple(count)].append(word)

        return list(groups.values())