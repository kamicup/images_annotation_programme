# Copyright 2017 The TensorFlow Authors. All Rights Reserved.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
# ==============================================================================

r"""Convert raw tags Json to pbtxt file.

Example usage:
    python convert_tag_json_to_pbtxt.py \
         ../resources/list_of_tags.json \
         label_map.pbtxt
"""
import json
import sys


json_file = "../resources/list_of_tags.json"
if len(sys.argv) > 1:
  json_file = sys.argv[1]

out_file = "label_map.pbtxt"
if len(sys.argv) > 2:
  out_file = sys.argv[2]


with open(json_file, encoding='utf-8') as f:
  j = json.load(f)
  with open(out_file, "w", encoding="utf-8") as of:
    i = 1
    for item in j:
      of.write("item { \n")
      of.write("  id: "+str(i)+"\n")
      of.write("  name: '"+item["name"]+"'\n")
      of.write("}\n\n")
    
      i = i + 1

print("Completed!!!"+str(i-1))
