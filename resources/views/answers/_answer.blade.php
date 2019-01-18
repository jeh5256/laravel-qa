<answer :answer="{{ $answer }}" inline-template>
    <div class="media post">
                            
        @include('shared._vote',[
            'model' => $answer
        ])
    
        <div class="media-body">
            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">

                    <textarea class="form-control" rows="10" v-model="body"></textarea>
                </div>
                <button @click="editing=false">Update</button>
                <button @click="editing=false">Cancel</button>
            </form>
            <div v-else>
                <div v-html="bodyHtml"></div>
                <div class="row">
                    <div class="col-4">
                        <div class="ml-auto">
        
                            @can ('update', $answer)
                                <a  @click.prevent="editing=true" class="btn btn-sm btn-outline-info">Edit</a>
                            @endcan
        
                            @can ('delete', $answer)
                                <form method="POST" action="{{ route('questions.answers.destroy', [$answer->id, $answer->id]) }}" class="form-delete">
                                    @method('delete')
                                    @csrf()
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onClick="return confirm('Are you sure?')">
                                            Delete
                                    </button>
                                </form>
                            @endcan
        
                        </div>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <author-info :model="{{ $answer }} " label="Answered"
                        ></author-info>
                    </div>
                </div>
            </div>
        </div>
    </div>
</answer>